<x-app-layout>
    <div class="min-h-screen bg-[#F8FAFC] dark:bg-slate-900 pb-20">
        {{-- Header Section --}}
        <div class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 mb-8">
            <div class="max-w-4xl mx-auto px-6 py-8">
                <nav class="flex mb-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                    <ol class="inline-flex items-center space-x-2">
                        <li>Dashboard</li>
                        <li><i class="fa-solid fa-chevron-right text-[7px]"></i></li>
                        <li>Data Petugas</li>
                        <li><i class="fa-solid fa-chevron-right text-[7px]"></i></li>
                        <li class="text-plnBlue">Edit Profil</li>
                    </ol>
                </nav>
                
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-slate-800 dark:text-white tracking-tight">
                            Edit Profil <span class="text-plnBlue font-extrabold italic uppercase">Petugas</span>
                        </h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                            Perbarui data personel untuk sinkronisasi laporan lapangan.
                        </p>
                    </div>
                    <a href="{{ route('karyawan.index') }}" 
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-200 border border-slate-200 dark:border-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-600 transition-all shadow-sm">
                        <i class="fa-solid fa-arrow-left-long"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        {{-- Form Container --}}
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white dark:bg-slate-800 rounded-[2rem] border border-slate-200 dark:border-slate-700 shadow-xl shadow-slate-200/50 dark:shadow-none overflow-hidden">
                
                <form action="{{ route('karyawan.update', $karyawan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Section 1: Profil Media --}}
                    <div class="p-8 md:p-10 bg-gradient-to-br from-slate-50 to-white dark:from-slate-900/50 dark:to-slate-800 border-b border-slate-100 dark:border-slate-700">
                        <div class="flex flex-col md:flex-row items-center gap-10">
                            <div class="relative group">
                                <div class="w-40 h-40 rounded-[2.5rem] overflow-hidden border-[6px] border-white dark:border-slate-700 shadow-2xl bg-slate-100 shrink-0">
                                    @if($karyawan->foto)
                                        <img id="preview-img" src="{{ asset('uploads/karyawan/'.$karyawan->foto) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    @else
                                        <div id="preview-placeholder" class="w-full h-full flex items-center justify-center bg-plnBlue text-white text-5xl font-black">
                                            {{ strtoupper(substr($karyawan->nama, 0, 1)) }}
                                        </div>
                                        <img id="preview-img" class="w-full h-full object-cover hidden transition-transform duration-500 group-hover:scale-110">
                                    @endif
                                </div>
                                <button type="button" onclick="document.getElementById('foto-input').click()" 
                                        class="absolute -bottom-2 -right-2 bg-plnBlue text-white p-4 rounded-2xl shadow-xl hover:scale-110 active:scale-95 transition-all border-4 border-white dark:border-slate-800">
                                    <i class="fa-solid fa-camera text-lg"></i>
                                </button>
                            </div>
                            
                            <div class="flex-1 text-center md:text-left">
                                <span class="px-3 py-1 bg-plnBlue/10 text-plnBlue text-[10px] font-black uppercase tracking-widest rounded-full">Foto Identitas</span>
                                <h3 class="text-xl font-bold text-slate-800 dark:text-white mt-3 italic uppercase">Pas Foto Resmi</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 max-w-md">
                                    Gunakan foto terbaru dengan latar belakang polos. Format yang didukung: <b>JPG, PNG, WebP</b>.
                                </p>
                                <input type="file" name="foto" id="foto-input" accept="image/*" class="hidden">
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Form Fields --}}
                    <div class="p-8 md:p-10">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            {{-- Nama --}}
                            <div class="space-y-2 md:col-span-2">
                                <label class="flex items-center gap-2 text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                    <i class="fa-solid fa-signature text-plnBlue"></i> Nama Lengkap Petugas
                                </label>
                                <input type="text" name="nama" value="{{ old('nama', $karyawan->nama) }}" required
                                    class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-900 border-2 border-transparent focus:border-plnBlue rounded-2xl font-bold text-slate-700 dark:text-slate-200 transition-all outline-none">
                            </div>

                            {{-- Jabatan --}}
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                    <i class="fa-solid fa-briefcase text-plnBlue"></i> Jabatan Struktural
                                </label>
                                <div class="relative">
                                    <select name="jabatan" required
                                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-900 border-2 border-transparent focus:border-plnBlue rounded-2xl font-bold text-slate-700 dark:text-slate-200 transition-all outline-none appearance-none cursor-pointer">
                                        @foreach(['Manager Unit', 'Supervisor Teknik', 'Staf Administrasi', 'Petugas Lapangan (Yantek)', 'Petugas P2TL', 'Team Leader'] as $jbtn)
                                            <option value="{{ $jbtn }}" {{ $karyawan->jabatan == $jbtn ? 'selected' : '' }}>{{ $jbtn }}</option>
                                        @endforeach
                                    </select>
                                    <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                                </div>
                            </div>

                            {{-- Umur --}}
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                    <i class="fa-solid fa-calendar-day text-plnBlue"></i> Umur (Tahun)
                                </label>
                                <input type="number" name="umur" value="{{ old('umur', $karyawan->umur) }}" min="18" max="80" required
                                    class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-900 border-2 border-transparent focus:border-plnBlue rounded-2xl font-bold text-slate-700 dark:text-slate-200 outline-none transition-all">
                            </div>

                            {{-- Tahun Masuk --}}
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                    <i class="fa-solid fa-clock-rotate-left text-plnBlue"></i> TMT (Tahun Masuk)
                                </label>
                                <input type="number" name="tahun_masuk" value="{{ old('tahun_masuk', $karyawan->tahun_masuk) }}" min="1990" max="{{ date('Y') }}" required
                                    class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-900 border-2 border-transparent focus:border-plnBlue rounded-2xl font-bold text-slate-700 dark:text-slate-200 outline-none transition-all">
                            </div>

                        </div>

                        {{-- Alert Errors --}}
                        @if ($errors->any())
                            <div class="mt-10 p-5 bg-rose-50 dark:bg-rose-900/20 border-l-4 border-rose-500 rounded-2xl flex gap-4 animate-shake">
                                <i class="fa-solid fa-triangle-exclamation text-rose-500 text-xl"></i>
                                <div>
                                    <p class="font-black text-rose-800 dark:text-rose-400 uppercase text-[10px] tracking-[0.2em] mb-2">Validasi Gagal</p>
                                    <ul class="text-xs text-rose-600 dark:text-rose-400 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li class="flex items-center gap-2 font-bold">• {{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Footer: Buttons --}}
                    <div class="p-8 md:p-10 bg-slate-50 dark:bg-slate-900/50 flex flex-col md:flex-row items-center justify-end gap-4">
                        <a href="{{ route('karyawan.index') }}" 
                           class="w-full md:w-auto px-10 py-4 text-slate-400 dark:text-slate-500 text-xs font-black uppercase tracking-widest hover:text-rose-500 transition-colors text-center">
                            Batalkan Perubahan
                        </a>
                        <button type="submit" 
                            class="w-full md:w-auto px-12 py-4 bg-plnBlue hover:bg-blue-700 text-white rounded-2xl text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-blue-500/20 dark:shadow-none transition-all flex items-center justify-center gap-3 active:scale-95">
                            <i class="fa-solid fa-floppy-disk text-sm"></i>
                            Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('foto-input').onchange = evt => {
            const [file] = evt.target.files
            if (file) {
                const preview = document.getElementById('preview-img');
                const placeholder = document.getElementById('preview-placeholder');
                
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
                if(placeholder) placeholder.classList.add('hidden');
            }
        }
    </script>

    <style>
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        .animate-shake { animation: shake 0.3s ease-in-out; }
    </style>
</x-app-layout>