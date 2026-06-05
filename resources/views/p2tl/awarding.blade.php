<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-10 space-y-12">
        
        {{-- Header --}}
        <div class="text-center space-y-2">
            <h1 class="text-4xl font-black text-slate-800 tracking-tight uppercase">
                <i class="fa-solid fa-trophy text-yellow-500 mr-2"></i> 
                P2TL <span class="text-blue-600">Achievement</span>
            </h1>
            <p class="text-slate-500 font-medium italic">Apresiasi Petugas dengan Temuan kWh TS Terbanyak</p>
        </div>

        {{-- Podium Section (Top 3) --}}
        @if($awards->count() >= 3)
        <div class="flex flex-col md:flex-row items-end justify-center gap-6 md:gap-4 pt-10">
            
            <div class="w-full md:w-64 bg-white rounded-t-3xl border-x border-t border-slate-200 p-6 text-center shadow-lg order-2 md:order-1 h-72 flex flex-col justify-between">
                <div>
                    <div class="w-16 h-16 bg-slate-100 rounded-full mx-auto flex items-center justify-center border-4 border-slate-200 mb-4">
                        <span class="text-xl font-black text-slate-400">2</span>
                    </div>
                    <h3 class="font-bold text-slate-700 uppercase leading-tight">{{ $awards[1]->nama_petugas }}</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase">{{ $awards[1]->unit_ulp }}</p>
                </div>
                <div class="bg-slate-50 rounded-2xl py-3 mt-4">
                    <p class="text-2xl font-black text-slate-600">{{ number_format($awards[1]->total_kwh, 0, ',', '.') }}</p>
                    <p class="text-[9px] font-bold text-slate-400">TOTAL kWh TS</p>
                </div>
            </div>

            <div class="w-full md:w-72 bg-gradient-to-b from-yellow-400 to-yellow-600 rounded-t-[3rem] p-8 text-center shadow-2xl order-1 md:order-2 h-[22rem] flex flex-col justify-between relative transform md:scale-110">
                <div class="absolute -top-8 left-1/2 -translate-x-1/2">
                    <i class="fa-solid fa-crown text-yellow-300 text-5xl drop-shadow-md"></i>
                </div>
                <div class="pt-4">
                    <div class="w-20 h-20 bg-white/20 rounded-full mx-auto flex items-center justify-center border-4 border-white/50 mb-4 backdrop-blur-sm">
                        <span class="text-2xl font-black text-white">1</span>
                    </div>
                    <h3 class="font-black text-white text-xl uppercase leading-tight">{{ $awards[0]->nama_petugas }}</h3>
                    <p class="text-[10px] text-white/80 font-bold uppercase tracking-widest">{{ $awards[0]->unit_ulp }}</p>
                </div>
                <div class="bg-white/20 rounded-3xl py-4 mt-4 backdrop-blur-md">
                    <p class="text-4xl font-black text-white">{{ number_format($awards[0]->total_kwh, 0, ',', '.') }}</p>
                    <p class="text-[10px] font-bold text-yellow-100 uppercase tracking-widest">TOTAL kWh TS</p>
                </div>
            </div>

            <div class="w-full md:w-64 bg-white rounded-t-3xl border-x border-t border-slate-200 p-6 text-center shadow-lg order-3 h-64 flex flex-col justify-between">
                <div>
                    <div class="w-16 h-16 bg-orange-50 rounded-full mx-auto flex items-center justify-center border-4 border-orange-100 mb-4">
                        <span class="text-xl font-black text-orange-300">3</span>
                    </div>
                    <h3 class="font-bold text-slate-700 uppercase leading-tight">{{ $awards[2]->nama_petugas }}</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase">{{ $awards[2]->unit_ulp }}</p>
                </div>
                <div class="bg-orange-50/50 rounded-2xl py-3 mt-4">
                    <p class="text-2xl font-black text-orange-600">{{ number_format($awards[2]->total_kwh, 0, ',', '.') }}</p>
                    <p class="text-[9px] font-bold text-orange-300">TOTAL kWh TS</p>
                </div>
            </div>
        </div>
        @endif

        {{-- Table for Rank 4-10 --}}
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-50 bg-slate-50/50">
                <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Top 10 Performers</h4>
            </div>
            <table class="w-full text-left">
                <tbody class="divide-y divide-slate-50">
                    @foreach($awards->slice(3) as $key => $rank)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-8 py-5 w-20">
                            <span class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-xs font-black text-slate-400">
                                {{ $key + 1 }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <p class="font-black text-slate-700 uppercase tracking-tight">{{ $rank->nama_petugas }}</p>
                            <p class="text-[10px] text-slate-400 font-bold">{{ $rank->unit_ulp }}</p>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <p class="text-lg font-black text-blue-600">{{ number_format($rank->total_kwh, 0, ',', '.') }}</p>
                            <p class="text-[9px] font-bold text-slate-300 uppercase">kWh TS Terdeteksi</p>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>