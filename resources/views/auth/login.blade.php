<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        
        .pln-hero-bg {
            background-image: url("{{ asset('images/Dashboard-PLN.jpeg') }}");
            background-size: cover;
            background-position: center;
        }
    </style>

    <div class="grid grid-cols-1 lg:grid-cols-12 min-h-screen font-sans bg-white">

        <div class="hidden lg:flex lg:col-span-7 xl:col-span-8 relative pln-hero-bg">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/90 via-black/40 to-transparent"></div>

            <div class="relative z-10 flex flex-col justify-between p-16 text-white w-full">
                <div>
                    <img src="{{ asset('images/Logo-PLN.png') }}" alt="PLN Logo" class="h-16 w-auto">
                </div>

                <div class="max-w-2xl space-y-6">
                    <h5 class="text-6xl xl:text-7xl font-extrabold leading-tight tracking-tight">
                        Sistem Manajemen dan Analisa Kinerja Tim
                        <span class="text-blue-400">P2TL</span>
                    </h5>
                    <p class="text-lg text-white/80 leading-relaxed max-w-lg">
                        Sistem Informasi Terpusat Monitoring Kinerja Personel <br>PT PLN (Persero) UID Lampung.
                    </p>
                </div>

                <div class="text-sm text-white/40">
                    &copy; {{ date('Y') }} PT PLN (Persero) UID Lampung. All rights reserved.
                </div>
            </div>
        </div>

      <div class="col-span-12 lg:col-span-5 xl:col-span-4 flex items-center justify-center bg-white p-8 sm:p-16">
    <div class="w-full max-w-sm">
        
        <div class="mb-12">
            <img src="{{ asset('images/Logo-PLN.png') }}" alt="PLN Logo" class="h-12 w-auto mb-8">
            <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Sign In</h2>
            <p class="text-slate-500 mt-2 text-sm">Selamat datang kembali. Silakan masukkan kredensial akses internal Anda.</p>
        </div>

        @if ($errors->any())
            <div class="mb-8 p-4 bg-red-50 border-l-2 border-red-500 rounded-lg animate-in fade-in duration-500">
                <p class="text-xs text-red-700 font-semibold uppercase tracking-wider">Akses Gagal</p>
                <p class="text-xs text-red-600/80 mt-1 font-medium italic">Kombinasi username atau password tidak valid.</p>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div class="space-y-1.5">
                <label for="username" class="text-xs font-bold text-slate-700 uppercase tracking-widest">Username</label>
                <div class="relative group">
                    <input type="text" name="username" value="{{ old('username') }}" required autofocus
                        class="w-full bg-white border-b-2 border-slate-200 py-3 focus:border-plnBlue outline-none transition-all duration-300 text-base font-medium text-slate-800 placeholder:text-slate-300"
                        placeholder="Masukkan username">
                </div>
            </div>

            <div class="space-y-1.5">
                <div class="flex justify-between items-center">
                    <label for="password" class="text-xs font-bold text-slate-700 uppercase tracking-widest">Password</label>
                </div>
                <div class="relative group">
                    <input type="password" id="password" name="password" required
                        class="w-full bg-white border-b-2 border-slate-200 py-3 pr-10 focus:border-plnBlue outline-none transition-all duration-300 text-base font-medium text-slate-800 placeholder:text-slate-300"
                        placeholder="••••••••">
                    <button type="button" id="toggle-password" class="absolute right-0 top-1/2 -translate-y-1/2 text-slate-400 hover:text-plnBlue transition-colors p-2">
                        <i class="fa-solid fa-eye-low-vision text-sm"></i>
                    </button>
                </div>
            </div>

         <div class="pt-6">
    <button type="submit"
        class="w-full flex items-center justify-center gap-3 bg-[#00A2E9] hover:bg-[#00B4FF] text-white py-4 rounded-xl font-bold transition-all duration-300 shadow-lg shadow-blue-100 hover:shadow-blue-200 active:scale-[0.98] group">
        
        <span class="tracking-widest uppercase text-xs">Masuk ke Sistem</span>
        
        
            <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform duration-300"></i>
        </div>
    </button>
</div>
        </form>
    </div>

    <script>
        const btn = document.getElementById('toggle-password');
        const input = document.getElementById('password');
        btn.addEventListener('click', () => {
            const type = input.type === 'password' ? 'text' : 'password';
            input.type = type;
            btn.innerHTML = type === 'password' ? '<i class="fa-solid fa-eye"></i>' : '<i class="fa-solid fa-eye-slash"></i>';
        });
    </script>
</x-guest-layout>