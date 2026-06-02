<x-guest-layout>
    <div id="success-popup" class="hidden fixed inset-0 flex items-center justify-center z-50" style="background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);">
        <div style="background: #fff; border-radius: 20px; padding: 2.5rem 2rem; text-align: center; max-width: 340px; width: 90%; box-shadow: 0 25px 60px rgba(0,0,0,0.2); animation: popIn 0.3s ease;">
        
            <div style="width: 72px; height: 72px; background: linear-gradient(135deg, #d1fae5, #6ee7b7); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem;">
                <svg style="width: 36px; height: 36px; color: #059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #111827; margin-bottom: 0.5rem;">Verifikasi Berhasil!</h2>
            <p style="font-size: 0.875rem; color: #6b7280; line-height: 1.6;">
                Email Anda berhasil diverifikasi.<br>Silakan tunggu approval admin sebelum dapat menggunakan akun.
            </p>
        </div>
    </div>

  

    {{-- Ikon Amplop --}}
    <div style="text-align: center; margin-bottom: 1.5rem;">
        <div style="width: 64px; height: 64px; background: linear-gradient(135deg, #fef3c7, #fde68a); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
            <svg style="width: 32px; height: 32px; color: #d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h1 style="font-size: 1.125rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem;">Verifikasi Email Anda</h1>
        <p style="font-size: 0.8rem; color: #9ca3af;">Satu langkah lagi untuk mengaktifkan akun Anda</p>
    </div>

    {{-- Kotak Instruksi --}}
    <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1rem 1.25rem; margin-bottom: 1rem;">
        <p style="font-size: 0.875rem; color: #4b5563; line-height: 1.7; margin: 0;">
            Kami telah mengirimkan <strong>link verifikasi</strong> ke email yang Anda daftarkan.
            Silakan buka email tersebut dan klik tombol verifikasi di dalamnya.
        </p>
    </div>

    {{-- Hint link expired --}}
    <p style="font-size: 0.8rem; color: #9ca3af; line-height: 1.6; margin-bottom: 1.25rem;">
        Jika link verifikasi sudah kedaluwarsa, klik tombol di bawah untuk mendapatkan link baru.
    </p>

    {{-- Notifikasi Kirim Ulang --}}
    @if (session('status') == 'verification-link-sent')
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 0.75rem 1rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg style="width: 16px; height: 16px; color: #16a34a; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span style="font-size: 0.8rem; color: #15803d; font-weight: 500;">
                Link verifikasi baru telah dikirim ke email Anda.
            </span>
        </div>
    @endif

    {{-- Tombol Kirim Ulang & Logout --}}
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem;">
        <form method="POST" action="{{ route('verification.send') }}" style="flex: 1;">
            @csrf
            <button type="submit" style="width: 100%; padding: 0.625rem 1rem; background: linear-gradient(135deg, #92400e, #b45309); color: #fff; font-size: 0.875rem; font-weight: 600; border: none; border-radius: 8px; cursor: pointer; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                Kirim Ulang Link Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="font-size: 0.8rem; color: #9ca3af; background: none; border: none; cursor: pointer; text-decoration: underline; padding: 0.625rem 0; transition: color 0.2s;" onmouseover="this.style.color='#4b5563'" onmouseout="this.style.color='#9ca3af'">
                Keluar
            </button>
        </form>
    </div>


    <style>
        @keyframes popIn {
            from { opacity: 0; transform: scale(0.85); }
            to   { opacity: 1; transform: scale(1); }
        }
    </style>
    <script>
        setInterval(function() {
            fetch('/check-verification')
                .then(response => response.json())
                .then(data => {
                    if (data.verified) {
                        document.getElementById('success-popup').classList.remove('hidden');
                    }
                })
                .catch(error => console.error('Error checking verification:', error));
        }, 3000);
    </script>
</x-guest-layout>
