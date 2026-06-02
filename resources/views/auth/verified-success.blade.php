<x-guest-layout>
    <div class="text-center py-8">
        <!-- Ikon Sukses (Ceklis) -->
        <div class="flex justify-center mb-6">
            <div class="bg-green-100 p-4 rounded-full">
                <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-2">Email Berhasil Diverifikasi!</h2>

        @if (session('status'))
            <p class="text-gray-600 mb-6">
                {{ session('status') }}
            </p>
        @else
            <p class="text-gray-600 mb-6">
                Email Anda berhasil diverifikasi. Silakan tunggu approval admin sebelum dapat login.
            </p>
        @endif

        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6">
            <p class="text-sm text-yellow-700">
                <strong>Catatan:</strong> Akun Anda masih memerlukan persetujuan dari Admin sebelum dapat digunakan untuk login.
                Anda akan dihubungi jika akun sudah disetujui.
            </p>
        </div>

        <div class="space-y-4">
            <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 transition duration-150">
                Kembali ke Halaman Login
            </a>
        </div>
    </div>
</x-guest-layout>
