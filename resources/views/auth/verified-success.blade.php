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
        <p class="text-gray-600 mb-8">
            Akun Kopi Elang Mas Anda sekarang sudah aktif. 
            Silakan kembali ke tab sebelumnya untuk melanjutkan pemesanan, atau klik tombol di bawah ini.
        </p>

        <div class="space-y-4">
            <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 transition duration-150">
                Lanjut ke Katalog Produk
            </a>
            
            <p id="closing-msg" class="text-xs text-gray-400 mt-4">
                Tip: Anda bisa menutup tab ini sekarang.
            </p>
        </div>
    </div>
    <script>
        // Mencoba menutup tab otomatis setelah 1 detik
        setTimeout(function() {
            window.close();
            
            // Jika gagal (diblokir browser), beri tahu user
            setTimeout(function() {
                document.getElementById('closing-msg').innerText = "Silakan tutup tab ini secara manual dan kembali ke tab utama Anda.";
            }, 500);
        }, 1000);
    </script>
</x-guest-layout>
