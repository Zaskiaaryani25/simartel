<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\P2tlRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil hitungan dasar (Tetap cepat karena hanya mengambil angka total baris)
        $totalPetugas = Karyawan::count();
        $targetUnit = $totalPetugas * 85; 

        // 2. OPTIMASI UTAMA: Hitung agregat langsung lewat database SQL (Hanya 1 baris hasil yang ditarik ke memori)
        $aggregates = P2tlRecord::select(
            DB::raw('COUNT(*) as total_periksa'),
            DB::raw('SUM(kwh_ts) as total_kwh'),
            DB::raw('MAX(updated_at) as last_update')
        )->first();

        $totalPeriksa = $aggregates->total_periksa ?? 0;
        $totalKwh = $aggregates->total_kwh ?? 0;
        $lastUpdate = $aggregates->last_update;

        // 3. OPTIMASI KEDUA: Hitung klasifikasi status langsung lewat Query Group By (Hanya menarik 5 baris data status)
        $statusCounts = P2tlRecord::select('update_status', DB::raw('COUNT(*) as total'))
            ->whereIn('update_status', ['Temuan - P1', 'Temuan - P2', 'Temuan - P3', 'Temuan - P4', 'Temuan - K2'])
            ->groupBy('update_status')
            ->pluck('total', 'update_status');

        // Petakan ke array $bh agar struktur tidak merusak template Blade Anda
        $bh = [
            'p1' => $statusCounts['Temuan - P1'] ?? 0,
            'p2' => $statusCounts['Temuan - P2'] ?? 0,
            'p3' => $statusCounts['Temuan - P3'] ?? 0,
            'p4' => $statusCounts['Temuan - P4'] ?? 0,
            'k2' => $statusCounts['Temuan - K2'] ?? 0,
        ];

        // 4. Hitung total temuan (gabungan P1-K2)
        $totalTemuan = array_sum($bh);

        // 5. Hitung Persentase Kontribusi tiap kategori untuk tabel breakdown
        $kontribusi = [];
        foreach ($bh as $key => $value) {
            $kontribusi[$key] = $totalTemuan > 0 ? round(($value / $totalTemuan) * 100, 1) : 0;
        }

        // 6. Bungkus dalam variabel $data (Sama persis seperti struktur lama agar Blade tidak error)
        $data = [
            'total_periksa'  => $totalPeriksa,
            'target_unit'    => $targetUnit,
            'total_temuan'   => $totalTemuan,
            'total_kwh'      => $totalKwh,
            'hit_rate'       => $totalPeriksa > 0 ? round(($totalTemuan / $totalPeriksa) * 100, 2) : 0,
            'capaian_target' => $targetUnit > 0 ? round(($totalPeriksa / $targetUnit) * 100, 1) : 0,
            'bh'             => $bh,
            'kontribusi'     => $kontribusi,
            'last_update'    => $lastUpdate,
        ];

        return view('dashboard', compact('totalPetugas', 'data'));
    }

    public function ulpIndex()
    {
        $mappingUlp = [
            '17300' => 'ULP BUMI ABUNG', '17330' => 'ULP MENGGALA', '17340' => 'ULP PULUNG KENCANA',
            '17350' => 'ULP MESUJI', '17360' => 'ULP BUKIT KEMUNING', '17370' => 'ULP BLAMBANGAN UMPU',
            '17200' => 'ULP METRO', '17210' => 'ULP SRIBAWONO', '17220' => 'ULP BANDAR JAYA',
            '17270' => 'ULP RUMBIA', '17280' => 'ULP SUKADANA', '17400' => 'ULP PRINGSEWU',
            '17410' => 'ULP TALANG PADANG', '17420' => 'ULP KOTA AGUNG', '17430' => 'ULP KALIREJO',
            '17440' => 'ULP LIWA', '17100' => 'ULP KARANG', '17110' => 'ULP NATAR',
            '17120' => 'ULP WAY HALIM', '17130' => 'ULP KALIANDA', '17131' => 'ULP SIDOMULYO', 
            '17150' => 'ULP SUTAMI', '17180' => 'ULP TELUK BETUNG',
        ];

        $ulpStats = P2tlRecord::select('unit_ulp', 
                    DB::raw('SUM(kwh_ts) as total_kwh'), 
                    DB::raw('COUNT(*) as total_periksa'))
                ->groupBy('unit_ulp')
                ->orderBy('total_kwh', 'desc')
                ->get()
                ->map(function($item) use ($mappingUlp) {
                    $kode = trim($item->unit_ulp); 
                    $item->nama_display = $mappingUlp[$kode] ?? 'UNIT TIDAK TERDAFTAR';
                    $item->kode_display = $kode;
                    return $item;
                });

        return view('ulp.index', compact('ulpStats'));
    }
}