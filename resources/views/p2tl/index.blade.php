<x-app-layout>
    {{-- Background & Container Utama --}}
    <div class="min-h-screen bg-[#F8FAFC] pb-20">
        
        {{-- Header Section Resmi --}}
        <div class="bg-white border-b border-slate-200 mb-8">
            <div class="max-w-7xl mx-auto px-4 md:px-6 py-6">
                {{-- Breadcrumb --}}
                <nav class="flex mb-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li>Dashboard</li>
                        <li><i class="fa-solid fa-chevron-right text-[8px] mx-2"></i></li>
                        <li class="text-plnBlue">Rekapitulasi P2TL</li>
                    </ol>
                </nav>

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-slate-800 tracking-tight">
                            Rekapitulasi <span class="text-plnBlue font-extrabold">P2TL</span>
                        </h1>
                        <p class="text-sm text-slate-500 mt-1">
                            Manajemen data pemeriksaan kWh TS - UID Lampung.
                        </p>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col md:flex-row items-stretch md:items-center gap-4">
                        
                        {{-- Form Kosongkan Data --}}
                        <form action="{{ route('p2tl.truncate') }}" method="POST" 
                              id="truncateForm"
                              class="flex">
                            @csrf
                            @method('DELETE')
                            <button type="button" 
                                    onclick="confirmTruncate()"
                                    class="h-[42px] px-6 bg-white text-rose-600 border border-rose-200 rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-rose-50 hover:border-rose-300 transition-all flex items-center justify-center gap-3 shadow-sm group">
                                <i class="fa-solid fa-trash-can text-base group-hover:animate-bounce"></i>
                                <span>Hapus Data</span>
                            </button>
                        </form>

                        {{-- Form Import Berkas --}}
                        <form id="importForm" action="{{ route('p2tl.import') }}" method="POST" enctype="multipart/form-data" 
                              class="flex flex-grow items-center gap-3 p-2 bg-white border border-slate-200 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300">
                            @csrf
                            
                            <div class="relative group flex-grow lg:min-w-[200px]">
                                <input type="file" name="file" id="fileInput" accept=".xlsx, .csv"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" 
                                       onchange="updateFileName(this)">
                                
                                <div id="fileBox" 
                                     class="flex items-center h-[42px] px-3 text-[11px] font-bold text-slate-500 bg-slate-50/50 border border-dashed border-slate-300 rounded-xl group-hover:border-emerald-400 group-hover:bg-emerald-50/30 transition-all duration-300 truncate">
                                    <div id="fileIconWrapper" class="flex items-center">
                                        <i class="fa-solid fa-cloud-arrow-up mr-3 text-slate-400 group-hover:text-emerald-500 transition-colors text-sm"></i>
                                    </div>
                                    <span id="fileNameLabel" class="truncate uppercase tracking-wider italic">Pilih Berkas EPM (.xlsx / .csv)</span>
                                </div>
                            </div>

                            <button type="submit" id="btnImport"
                                    class="h-[42px] px-6 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[11px] font-black uppercase tracking-widest transition-all active:scale-95 flex items-center gap-2 shadow-sm shadow-emerald-100 shrink-0">
                                <i class="fa-solid fa-file-import text-sm"></i>
                                <span>Import</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Table Section --}}
        <div class="max-w-7xl mx-auto px-4 md:px-6 mb-6">
            <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse" style="min-width: 1700px;">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center w-16">No</th>
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">ID P2TL</th>
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">ID Pelanggan</th>
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">No Agenda</th>
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Status</th>
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">kWh TS</th>
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Tarif</th>
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Tegangan R-N</th>
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Unit ULP</th>
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Petugas (Username)</th>
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Tgl Periksa</th>
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Periode</th>
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-[11px]">
                            @forelse($records as $index => $r)
                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-6 py-5 text-center font-bold text-slate-300 group-hover:text-plnBlue">
                                    {{ $records->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-5 font-mono text-[9px] text-slate-400 italic">
                                    {{ $r->id_p2tl }}
                                </td>
                                <td class="px-6 py-5 font-black text-plnBlue text-[13px] tracking-tight">
                                    {{ $r->idpel }}
                                </td>
                                <td class="px-6 py-5 font-bold text-slate-700 italic">
                                    {{ $r->no_agenda }}
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full border border-slate-200 bg-slate-50 text-slate-600 font-black text-[10px] uppercase tracking-wider whitespace-nowrap">
                                        {{ $r->update_status ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right font-black text-rose-600 text-sm">
                                    {{ number_format($r->kwh_ts, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-5 text-center font-bold text-slate-700">
                                    {{ $r->tarif }}
                                </td>
                                <td class="px-6 py-5 text-center font-black text-slate-700">
                                    {{ $r->tegangan_r_n ?? '-' }} <span class="text-[9px] font-normal text-slate-400">V</span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg font-black text-[10px] border border-slate-200 uppercase tracking-tighter">
                                        {{ $r->unit_ulp }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="font-black text-slate-800 uppercase tracking-tight">{{ $r->nama_petugas }}</div>
                                    <div class="text-[9px] text-blue-500 font-bold lowercase italic opacity-70">@ {{ $r->username }}</div>
                                </td>
                                <td class="px-6 py-5 text-center font-bold text-slate-700">
                                    {{ $r->waktu_periksa ? \Carbon\Carbon::parse($r->waktu_periksa)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-5 text-center font-black text-slate-400 tracking-tighter">
                                    {{ $r->tgl }}/{{ $r->bulan }}/{{ $r->tahun }}
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <form action="{{ route('p2tl.destroy', $r->id) }}" method="POST" id="delete-form-{{ $r->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmSingleDelete('delete-form-{{ $r->id }}')" 
                                                class="text-rose-400 hover:text-rose-600 transition-colors p-2 rounded-xl hover:bg-rose-50">
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="13" class="py-32 text-center">
                                    <div class="flex flex-col items-center opacity-30">
                                        <i class="fa-solid fa-inbox text-4xl mb-3 text-slate-300"></i>
                                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">Belum ada rekap data</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-8 py-6 bg-slate-50/80 border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        Total: {{ number_format($records->total(), 0, ',', '.') }} Records
                    </span>
                    <div class="scale-90 origin-center md:origin-right font-bold">
                        {{ $records->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Loading Overlay Section --}}
    <div id="globalLoading" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[9999] flex flex-col items-center justify-center transition-opacity duration-300">
        <div class="bg-white p-8 rounded-3xl shadow-xl flex flex-col items-center gap-5 max-w-xs w-full border border-slate-100">
            <div class="flex items-center justify-center h-12">
                <i class="fa-solid fa-circle-notch animate-spin text-4xl text-plnBlue"></i>
            </div>
            <div class="text-center">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest">Memproses Data</h3>
                <p class="text-[10px] text-slate-400 mt-1 font-medium">Sinkronisasi sedang berlangsung...</p>
            </div>
            <div class="w-full mt-2">
                <div class="flex justify-between mb-1.5">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Status</span>
                    <span id="progressText" class="text-[10px] font-bold text-plnBlue">0%</span>
                </div>
                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                    <div id="progressBar" class="bg-plnBlue h-full transition-all duration-500 ease-out" style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Section --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // 1. Inisialisasi Toast tetap sama
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000, // Diperlama sedikit agar user sempat melihat
            timerProgressBar: true
        });

        // 2. Gunakan Blade Echo dengan tanda kutip untuk menangkap session
        // Ini cara paling aman agar tidak crash jika data kosong
        const msgSuccess = "{{ session('success') }}";
        const msgError = "{{ session('error') ?? $errors->first() }}";

        // 3. Eksikusi notifikasi
        // gunakan trim() untuk memastikan tidak ada spasi kosong yang terbawa
        if (msgSuccess && msgSuccess.trim() !== "") {
            Toast.fire({
                icon: 'success',
                title: msgSuccess
            });
        }

        if (msgError && msgError.trim() !== "") {
            Toast.fire({
                icon: 'error',
                title: msgError
            });
        }

        // Fungsi Konfigurasi hapus satu data
        function confirmSingleDelete(formId) {
            Swal.fire({
                title: 'Hapus data?',
                icon: 'warning',
                text: "Data ini akan dihapus.",
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

        // Fungsi Konfirmasi Kosongkan Seluruh Data
        function confirmTruncate() {
            Swal.fire({
                title: 'Kosongkan Seluruh Data?',
                text: "Tindakan ini menghapus semua rekap P2TL!",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Kosongkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('truncateForm').submit();
                }
            });
        }

        function updateFileName(input) {
            const label = document.getElementById('fileNameLabel');
            const box = document.getElementById('fileBox');
            const iconWrapper = document.getElementById('fileIconWrapper');
            
            if (input.files && input.files.length > 0) {
                const name = input.files[0].name;
                label.innerText = name;
                label.classList.remove('text-slate-500', 'italic');
                label.classList.add('text-emerald-700');
                
                box.classList.remove('bg-slate-50/50', 'border-slate-300', 'border-dashed');
                box.classList.add('bg-emerald-50', 'border-emerald-200', 'border-solid');
                
                iconWrapper.innerHTML = '<i class="fa-solid fa-file-excel mr-3 text-emerald-600 animate-bounce text-base"></i>';
            }
        }

document.getElementById('importForm').onsubmit = function() {
        const btn = document.getElementById('btnImport');
        const fileInput = document.getElementById('fileInput');

        if (!fileInput.files.length) {
            Toast.fire({ icon: 'error', title: 'Pilih file terlebih dahulu!' });
            return false;
        }

        // Efek visual sederhana agar user tahu proses sedang berjalan
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner animate-spin"></i> <span>Sedang Memproses...</span>';
        btn.classList.add('opacity-50', 'cursor-not-allowed');

        return true; // Form akan submit secara normal
    };

    // Fungsi update file name tetap dipertahankan
    function updateFileName(input) {
        const label = document.getElementById('fileNameLabel');
        if (input.files.length > 0) {
            label.innerText = input.files[0].name;
            label.classList.add('text-emerald-700');
        }
    }

    </script>
</x-app-layout>