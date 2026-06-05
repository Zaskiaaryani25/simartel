<x-app-layout>
    {{-- Background Dashboard yang lebih bersih --}}
    <div class="min-h-screen bg-[#F8FAFC] pb-20">

        {{-- Header Section dengan gaya Navbar/Sub-header Resmi --}}
        <div class="bg-white border-b border-slate-200 mb-8">
            <div class="max-w-7xl mx-auto px-4 md:px-6 py-6">
                <nav class="flex mb-4 text-xs font-semibold text-slate-400 uppercase tracking-wider" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">Dashboard</li>
                    </ol>
                </nav>

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-slate-800 tracking-tight">
                            Monitoring Capaian <span class="text-plnBlue">P2TL</span>
                        </h1>
                        <p class="text-sm text-slate-500 mt-1">
                            Unit Induk Distribusi Lampung
                        </p>
                    </div>

                    <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-lg border border-slate-100">
                        <div class="h-10 w-10 rounded-full bg-plnBlue/10 flex items-center justify-center text-plnBlue">
                            <i class="fa-solid fa-users-gear text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase leading-none">Total Personel</p>
                            <p class="text-lg font-bold text-slate-800">{{ $totalPetugas }} <span class="text-xs font-normal text-slate-500">Petugas</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 md:px-6 space-y-8">

            {{-- Statistics Grid - Lebih Kotak & Formal --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                {{-- Total Periksa --}}
                <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Periksa</p>
                    <div class="flex items-end justify-between mt-4">
                        <h2 class="text-3xl font-bold text-slate-800">{{ number_format($data['total_periksa']) }}</h2>
                        <span class="text-[10px] font-bold px-2 py-1 bg-blue-50 text-blue-700 rounded border border-blue-100">
                            Target: {{ number_format($data['target_unit']) }}
                        </span>
                    </div>
                </div>

                {{-- Total Temuan --}}
                <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Temuan</p>
                    <div class="flex items-end justify-between mt-4">
                        <h2 class="text-3xl font-bold text-emerald-600">{{ number_format($data['total_temuan']) }} <span class="text-sm text-slate-400">BH</span></h2>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-emerald-600 flex items-center justify-end gap-1">
                                <i class="fa-solid fa-chart-line"></i> {{ $data['hit_rate'] }}% Hit Rate
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Energi kWh --}}
                <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 border-l-4 border-l-amber-500">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Energi (kWh TS)</p>
                    <h2 class="text-3xl font-bold text-slate-800 mt-4">{{ number_format($data['total_kwh'], 0, ',', '.') }}</h2>
                    <p class="text-[10px] font-medium text-slate-400 mt-1 italic">Estimasi Penyelamatan Energi</p>
                </div>

                {{-- Capaian Target - Style BUMN Dashboard --}}
                <div class="bg-plnBlue p-6 rounded-xl shadow-md text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-xs font-bold uppercase tracking-wider opacity-80">Realisasi Target</p>
                        <h2 class="text-4xl font-bold mt-2">{{ $data['capaian_target'] }}%</h2>
                        <div class="w-full bg-white/20 h-2 rounded-full mt-4 overflow-hidden">
                            <div class="bg-amber-400 h-full rounded-full" style="width: {{ $data['capaian_target'] }}%"></div>
                        </div>
                    </div>
                    <i class="fa-solid fa-bolt absolute -right-2 -bottom-2 text-6xl text-white/10"></i>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Category Breakdown Table - Gaya Tabel Laporan Resmi --}}
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Rincian Klasifikasi Temuan</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                    <th class="px-6 py-4">Jenis Pelanggaran</th>
                                    <th class="px-6 py-4 text-center">Jumlah Kasus</th>
                                    <th class="px-6 py-4 text-right">Persentase</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach(['p1', 'p2', 'p3', 'p4', 'k2'] as $type)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="text-xs font-semibold text-slate-700 uppercase tracking-tight">Kategori {{ strtoupper($type) }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm font-bold text-slate-800">{{ number_format($data['bh'][$type]) }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <div class="w-20 bg-slate-100 h-1.5 rounded-full hidden sm:block">
                                                <div class="bg-plnBlue h-full rounded-full" style="width: {{ $data['kontribusi'][$type] }}%"></div>
                                            </div>
                                            <span class="text-xs font-bold text-plnBlue">{{ $data['kontribusi'][$type] }}%</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Side Section --}}
                <div class="space-y-6">
                    {{-- Status Informasi --}}
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                        <div class="flex items-center gap-2 mb-4">
                            <i class="fa-solid fa-circle-nodes text-plnBlue"></i>
                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Status Integrasi</h4>
                        </div>
                        <div class="space-y-4">
                            <div class="p-3 bg-emerald-50 rounded-lg border border-emerald-100">
                                <p class="text-[11px] text-emerald-800 font-medium">
                                    <i class="fa-solid fa-check-double mr-1"></i> Terhubung dengan Database P2TL
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Actions - Menu Navigasi Formal --}}
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-4">Menu Manajemen</h4>
                        <div class="grid grid-cols-1 gap-2">
                            <a href="{{ route('karyawan.index') }}" class="flex items-center gap-3 px-4 py-3 bg-white hover:bg-slate-50 border border-slate-200 rounded-lg transition-all group">
                                <i class="fa-solid fa-chart-simple text-slate-400 group-hover:text-plnBlue"></i>
                                <span class="text-xs font-bold text-slate-600">Performa Personel</span>
                            </a>
                            <a href="{{ route('karyawan.sync') }}" class="flex items-center gap-3 px-4 py-3 bg-amber-500 hover:bg-amber-600 rounded-lg transition-all shadow-sm">
                                <i class="fa-solid fa-arrows-rotate text-white"></i>
                                <span class="text-xs font-bold text-white">Sinkronisasi Data Sekarang</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
