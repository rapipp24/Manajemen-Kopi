<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Tampilkan daftar user dengan filter tab.
     * Tab:
     *   - semua      : semua user
     *   - pending    : sales yang sudah verified email tapi belum disetujui
     *   - approved   : user yang sudah approved dan aktif
     *   - rejected   : user yang ditolak
     */
    public function index(Request $request)
    {
        $tab    = $request->input('tab', 'semua');
        $search = $request->input('search');

        $query = User::query();

        // Filter pencarian nama/email
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan tab
        switch ($tab) {
            case 'pending':
                $query->where('role', User::ROLE_SALES)
                      ->whereNotNull('email_verified_at')
                      ->where('approval_status', User::APPROVAL_PENDING);
                break;

            case 'approved':
                $query->where('approval_status', User::APPROVAL_APPROVED);
                break;

            case 'rejected':
                $query->where('approval_status', User::APPROVAL_REJECTED);
                break;

            default: // 'semua'
                break;
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        // Hitung pending untuk badge
        $pendingApprovalCount = User::where('role', User::ROLE_SALES)
            ->whereNotNull('email_verified_at')
            ->where('approval_status', User::APPROVAL_PENDING)
            ->count();

        return view('admin.users.index', compact('users', 'tab', 'pendingApprovalCount'));
    }

    /**
     * Form tambah user baru (oleh Admin).
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Simpan user baru yang dibuat oleh Admin.
     * User yang dibuat admin langsung approved dan aktif.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role'     => 'required|in:admin,sales',
            'phone'    => 'nullable|string|max:20',
        ]);

        User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'role'            => $request->role,
            'phone'           => $request->phone,
            // Dibuat admin = langsung aktif dan approved
            'is_active'       => true,
            'approval_status' => User::APPROVAL_APPROVED,
            'approved_at'     => now(),
            'approved_by'     => auth()->id(),
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Form edit user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update data user.
     * Jika user adalah ID 1, paksa is_active=true dan approval_status=approved.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role'     => 'required|in:admin,sales',
            'phone'    => 'nullable|string|max:20',
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
            'phone'     => $request->phone,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Proteksi User ID 1: selalu tetap aktif dan approved
        if ($user->id === 1) {
            $data['is_active']       = true;
            $data['approval_status'] = User::APPROVAL_APPROVED;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Hapus user. User ID 1 tidak boleh dihapus.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        if ($user->id === 1) {
            return back()->with('error', 'Akun admin utama tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'User berhasil dihapus.');
    }

    /**
     * Approve pendaftaran sales.
     * Hanya untuk sales dengan email_verified_at tidak null dan status pending.
     */
    public function approve(Request $request, User $user): RedirectResponse
    {
        // Pastikan hanya sales pendaftar publik yang bisa di-approve melalui flow ini
        if (! $user->isSales()) {
            return back()->with('error', 'Hanya akun Sales yang bisa di-approve melalui fitur ini.');
        }

        if (! $user->hasVerifiedEmail()) {
            return back()->with('error', 'Akun ini belum memverifikasi email. Tidak bisa disetujui.');
        }

        if (! $user->isPending()) {
            return back()->with('warning', 'Akun ini sudah berstatus ' . $user->approval_status . '.');
        }

        DB::transaction(function () use ($user) {
            $user->update([
                'approval_status' => User::APPROVAL_APPROVED,
                'is_active'       => true,
                'approved_at'     => now(),
                'approved_by'     => auth()->id(),
                'rejected_at'     => null,
                'rejection_reason'=> null,
            ]);
        });

        return redirect()->route('admin.users.index', ['tab' => 'pending'])
                         ->with('success', "Akun {$user->name} berhasil disetujui. Sales sekarang bisa login.");
    }

    /**
     * Reject pendaftaran sales dengan alasan opsional.
     */
    public function reject(Request $request, User $user): RedirectResponse
    {
        if (! $user->isSales()) {
            return back()->with('error', 'Hanya akun Sales yang bisa ditolak melalui fitur ini.');
        }

        if (! $user->isPending()) {
            return back()->with('warning', 'Akun ini sudah berstatus ' . $user->approval_status . '.');
        }

        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($user, $request) {
            $user->update([
                'approval_status'  => User::APPROVAL_REJECTED,
                'is_active'        => false,
                'rejected_at'      => now(),
                'rejection_reason' => $request->input('rejection_reason'),
                'approved_at'      => null,
                'approved_by'      => null,
            ]);
        });

        return redirect()->route('admin.users.index', ['tab' => 'pending'])
                         ->with('success', "Pendaftaran {$user->name} berhasil ditolak.");
    }
}
