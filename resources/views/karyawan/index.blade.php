<x-app-layout>
    {{-- Background Dashboard yang bersih sesuai tema sebelumnya --}}
    <div x-data="{ openModal: false, selectedUser: { stats: { bh: {}, kwh: {} } }, search: '' }" 
         class="min-h-screen bg-[#F8FAFC] pb-20">

        {{-- Header Section Gaya BUMN Resmi --}}
        <div class="bg-white border-b border-slate-200 mb-8">
            <div class="max-w-7xl mx-auto px-4 md:px-6 py-6">
                <nav class="flex mb-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li>Dashboard</li>
                        <li><i class="fa-solid fa-chevron-right text-[8px] mx-2"></i></li>
                        <li class="text-plnBlue">Data Petugas</li>
                    </ol>
                </nav>

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-slate-800 tracking-tight">
                            Direktori <span class="text-plnBlue">Petugas</span>
                        </h1>
                        <p class="text-sm text-slate-500 mt-1">
                            Manajemen personel dan pemantauan kinerja individu lapangan.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Search Bar Formal --}}
                        <div class="relative w-full md:w-64">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" x-model="search" placeholder="Cari nama petugas..." 
                                class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-plnBlue/20 focus:border-plnBlue transition-all outline-none">
                        </div>

                        <a href="{{ route('karyawan.sync') }}" 
                            class="px-4 py-2 bg-white text-slate-700 rounded-lg text-xs font-bold border border-slate-200 flex items-center gap-2 hover:bg-slate-50 transition shadow-sm">
                            <i class="fa-solid fa-arrows-rotate text-plnBlue"></i><span>Sinkronisasi</span>
                        </a>

                    <form action="{{ route('karyawan.truncate') }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDeleteAll(this.form)" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg text-xs font-bold flex items-center gap-2 hover:bg-red-700 transition shadow-sm">
                        <i class="fa-solid fa-trash-can"></i><span>Hapus Semua</span>
                    </button>
                </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 md:px-6">
           {{-- Grid Petugas --}}
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
    @forelse($karyawans as $k)
    <div x-show="search === '' || '{{ strtolower($k->nama) }}'.includes(search.toLowerCase())"
        @click="selectedUser = @js($k); openModal = true"
        class="group cursor-pointer">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md hover:border-plnBlue/30 transition-all duration-300">
            
            <div class="aspect-square bg-slate-50 relative overflow-hidden flex items-center justify-center border-b border-slate-100">
                @if($k->foto)
                    <img src="{{ asset('uploads/karyawan/'.$k->foto) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-300">
                        <i class="fa-solid fa-user-tie text-5xl"></i>
                    </div>
                @endif
             
            </div>
            
            <div class="p-4 bg-white">
                <h3 class="font-bold text-sm text-slate-800 truncate uppercase tracking-tight">{{ $k->nama }}</h3>
                <p class="text-[10px] font-semibold text-plnBlue uppercase mt-1">{{ $k->jabatan }}</p>
            </div>
        </div>
    </div>
    @empty
                <div class="col-span-full text-center py-20 bg-white rounded-xl border border-dashed border-slate-300">
                    <i class="fa-solid fa-user-slash text-slate-300 text-4xl mb-4"></i>
                    <p class="font-bold text-slate-400 text-sm tracking-wide">Data petugas belum tersedia atau tidak ditemukan.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Modal Detail Petugas - Gaya Formal --}}
        <template x-teleport="body">
            <div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="openModal = false"></div>
                
                <div class="relative w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto"
                     x-show="openModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95">
                    
                    <div class="flex flex-col md:flex-row">
                        {{-- Sidebar Modal --}}
                       {{-- Sidebar Modal - Foto Lebih Besar --}}
<div class="w-full md:w-[35%] bg-slate-50 p-6 md:p-10 border-r border-slate-200 flex flex-col items-center justify-start">
    <div class="flex flex-col items-center w-full">
        {{-- Frame Foto Diperbesar (w-48 h-48) --}}
        <div class="w-48 h-48 md:w-56 md:h-56 rounded-2xl overflow-hidden shadow-lg mb-8 border-4 border-white bg-white">
            <template x-if="selectedUser.foto">
                <img :src="'/uploads/karyawan/' + selectedUser.foto" 
                     class="w-full h-full object-cover transition duration-500 hover:scale-110">
            </template>
            <template x-if="!selectedUser.foto">
                <div class="w-full h-full bg-slate-200 text-slate-400 flex items-center justify-center text-6xl font-bold">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
            </template>
        </div>

        {{-- Info Utama --}}
        <h2 class="text-2xl font-black text-slate-800 text-center uppercase tracking-tight leading-tight" x-text="selectedUser.nama"></h2>
        <p class="text-sm font-bold text-plnBlue mt-3 uppercase tracking-widest px-4 py-1.5 bg-blue-50 rounded-full border border-blue-100" x-text="selectedUser.jabatan"></p>
    </div>
    
    {{-- Info Tambahan --}}
    <div class="w-full mt-10 space-y-4">
        <div class="flex justify-between items-center text-sm border-b border-slate-200 pb-3">
            <span class="text-slate-400 font-bold uppercase text-[10px]">Masa Kerja</span>
            <span class="text-slate-700 font-extrabold" x-text="(new Date().getFullYear() - selectedUser.tahun_masuk) + ' Tahun'"></span>
        </div>
        <div class="flex justify-between items-center text-sm border-b border-slate-200 pb-3">
            <span class="text-slate-400 font-bold uppercase text-[10px]">Usia Petugas</span>
            <span class="text-slate-700 font-extrabold" x-text="selectedUser.umur + ' Tahun'"></span>
        </div>
    </div>
</div>
                       
 {{-- Main Content Modal --}}
<div class="w-full md:w-2/3 flex flex-col h-full max-h-[90vh]">
    
    {{-- Area Konten (Scrollable) --}}
    <div class="p-8 overflow-y-auto flex-grow">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2 uppercase tracking-wider">
                <i class="fa-solid fa-chart-simple text-plnBlue"></i> Ringkasan Kinerja Individu
            </h3>
            <span class="text-[10px] font-bold px-2 py-1 bg-slate-100 text-slate-500 rounded uppercase">Official Record</span>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="p-4 bg-white border border-slate-200 rounded-xl text-center shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase">Total Periksa</p>
                <p class="text-2xl font-bold text-slate-800 mt-1" x-text="selectedUser.total_periksa || 0"></p>
            </div>
            <div class="p-4 bg-white border border-slate-200 rounded-xl text-center shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase">Hit Rate</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1" x-text="(selectedUser.hit_rate || 0) + '%'"></p>
            </div>
            <div class="p-4 bg-white border border-slate-200 rounded-xl text-center shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase">Total kWh TS</p>
                <p class="text-lg font-bold text-amber-600 mt-2" x-text="new Intl.NumberFormat('id-ID').format(selectedUser.total_akumulasi_kwh || 0)"></p>
            </div>
        </div>

        {{-- Tabel Data --}}
        <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
            <table class="w-full text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Kategori</th>
                        <th class="px-4 py-3 text-center">Jumlah (bh)</th>
                        <th class="px-4 py-3 text-right">Energi (kWh)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                    <template x-for="(val, key) in (typeof selectedUser.rincian_temuan === 'string' ? JSON.parse(selectedUser.rincian_temuan) : selectedUser.rincian_temuan)" :key="key">
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 uppercase text-slate-500" x-text="key"></td>
                            <td class="px-4 py-3 text-center" x-text="val.bh"></td>
                            <td class="px-4 py-3 text-right text-plnBlue" x-text="new Intl.NumberFormat('id-ID').format(val.kwh || 0)"></td>
                        </tr>
                    </template>
                    <tr class="bg-slate-50 font-bold text-slate-900">
                        <td class="px-4 py-4 uppercase">Total Akumulasi</td>
                        <td class="px-4 py-4 text-center" x-text="selectedUser.total_akumulasi_bh || 0"></td>
                        <td class="px-4 py-4 text-right text-plnBlue" x-text="new Intl.NumberFormat('id-ID').format(selectedUser.total_akumulasi_kwh || 0)"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

   {{-- Footer Modal / Actions --}}
<div class="p-6 bg-slate-50 border-t border-slate-200 flex flex-wrap gap-3">
    <a :href="'/karyawan/' + selectedUser.id + '/edit'" 
       class="flex-1 px-4 py-2.5 bg-plnBlue text-white text-center rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-blue-700 transition shadow-md flex items-center justify-center gap-2">
        <i class="fa-solid fa-user-pen"></i> Edit
    </a>

    {{-- Tombol Hapus Individu dengan SweetAlert --}}
    <form :action="'/karyawan/' + selectedUser.id" method="POST" class="flex-1">
        @csrf
        @method('DELETE')
        <button type="button" @click="confirmSingleDelete($el.form)" 
                class="w-full px-4 py-2.5 bg-white text-red-600 border border-red-200 rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-red-50 transition">
            <i class="fa-solid fa-trash-can mr-1"></i> Hapus
        </button>
    </form>

    <button @click="openModal = false" class="px-6 py-2.5 bg-slate-200 text-slate-700 rounded-lg text-xs font-bold uppercase hover:bg-slate-300 transition">
        Tutup
    </button>
</div>
    </div>
</div>

                </div>
            </div>
        </template>
    </div>
    {{-- Notifikasi SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true
        });

        const successMsg = "{{ session('success') }}";
        const errorMsg = "{{ session('error') ?? $errors->first() }}";

        if (successMsg) Toast.fire({ icon: 'success', title: successMsg });
        if (errorMsg) Toast.fire({ icon: 'error', title: errorMsg });
    });

    // Konfirmasi Hapus Semua
    function confirmDeleteAll(form) {
        Swal.fire({
            title: 'Hapus Semua Data?',
            text: "Seluruh data petugas akan dibersihkan!",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Ya, Hapus Semua!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    }

    // Konfirmasi Hapus Satu Petugas (Individu)
    function confirmSingleDelete(form) {
        Swal.fire({
            title: 'Hapus Petugas?',
            text: "Data ini akan dihapus.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    }
</script>
</x-app-layout>