<x-app-layout>
    <div class="min-h-screen bg-[#F8FAFC] pb-12">
        {{-- Header --}}
        <div class="bg-white border-b border-slate-200 mb-8">
            <div class="max-w-7xl mx-auto px-4 md:px-6 py-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">
                            Performa Unit <span class="text-plnBlue">Layanan (ULP)</span>
                        </h1>
                        <p class="text-sm text-slate-500 mt-1">Monitoring Capaian kWh TS Per Unit Kerja</p>
                    </div>
                    <div class="flex gap-2">
                        <span class="px-4 py-2 bg-plnBlue/10 text-plnBlue rounded-lg text-xs font-bold uppercase border border-plnBlue/20">
                            Total: {{ $ulpStats->count() }} Unit
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 md:px-6">
            {{-- Tabel Utama ULP --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center w-16">Rank</th>
                                <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">Nama Unit Layanan</th>
                                <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">Kode ULP</th>
                                <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">Total Periksa</th>
                                <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-right">Realisasi (kWh)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
    @foreach($ulpStats as $index => $ulp)
    <tr class="hover:bg-slate-50/80 transition-colors group">
        <td class="px-6 py-5 text-center">
            @if($index < 3)
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-100 text-amber-700 text-xs font-black">
                    {{ $index + 1 }}
                </span>
            @else
                <span class="text-xs font-bold text-slate-400">{{ $index + 1 }}</span>
            @endif
        </td>
        
        <td class="px-6 py-5">
            {{-- PERBAIKAN DI SINI: Panggil nama_display dari Controller --}}
            <p class="font-bold text-slate-700 uppercase tracking-tight group-hover:text-plnBlue transition-colors">
                {{ $ulp->nama_display }}
            </p>
            <p class="text-[9px] text-slate-500 font-medium">Unit Layanan Pelanggan</p>
        </td>

        <td class="px-6 py-5 text-center">
            {{-- Menampilkan Kode ULP (misal: 17300) --}}
            <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-black rounded-md border border-slate-200">
                {{ $ulp->kode_display }}
            </span>
        </td>

        <td class="px-6 py-5 text-center">
            <span class="text-sm font-bold text-slate-600">{{ number_format($ulp->total_periksa) }}</span>
            <span class="text-[10px] text-slate-400 font-medium ml-1">KALI</span>
        </td>

        <td class="px-6 py-5 text-right">
            <div class="flex flex-col items-end">
                <span class="text-sm font-black text-slate-800">{{ number_format($ulp->total_kwh, 0, ',', '.') }}</span>
                <span class="text-[9px] font-bold text-emerald-500 uppercase tracking-tighter">kWh TS</span>
            </div>
        </td>
    </tr>
    @endforeach
</tbody>
                    </table>
                </div>
            </div>

            {{-- Footer Info --}}
            <div class="mt-6 flex items-center gap-2 text-slate-400">
                <i class="fa-solid fa-circle-info text-xs"></i>
                <p class="text-[10px] font-medium italic">Data di atas merupakan hasil agregasi real-time dari tabel P2TL_Records berdasarkan input personel.</p>
            </div>
        </div>
    </div>
</x-app-layout>