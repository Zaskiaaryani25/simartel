<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\P2tlRecord;
use App\Models\Karyawan;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Imports\P2tlImport;

class P2tlController extends Controller
{
    public function index()
    {
        $records = P2tlRecord::latest()->paginate(10);
        return view('p2tl.index', compact('records'));
    }

public function import(Request $request)
{
    set_time_limit(0);
    ini_set('memory_limit', '2048M'); 

    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv|max:102400',
    ]);

    try {
        DB::connection()->disableQueryLog();
        
        $data = Excel::toArray([], $request->file('file'))[0];
        
        if (count($data) <= 1) {
            return back()->with('error', 'File Excel kosong atau hanya header');
        }

        array_shift($data); 
        $total = count($data);

        // Inisialisasi Progress Cache
        Cache::put('import_progress', 0);
        Cache::put('import_total', $total);
        Cache::put('import_cancelled', false);

        $inserted = 0;
        $duplicateCount = 0; // Tambahan untuk hitung data ganda
        $batchSize = 2000; 

        DB::beginTransaction();
        
        // Mematikan check untuk kecepatan maksimal
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('SET UNIQUE_CHECKS=0');

        foreach (array_chunk($data, $batchSize) as $chunkIdx => $chunk) {
            $records = [];
            
            // Ambil semua ID P2TL yang sudah ada di DB untuk batch ini saja (Optimasi Memori)
            $existingIds = DB::table('p2tl_records')
                            ->whereIn('id_p2tl', array_column($chunk, 1))
                            ->pluck('id_p2tl')
                            ->toArray();

            foreach ($chunk as $row) {
                if (empty($row[2])) continue; // Skip jika IDPEL kosong

                // VALIDASI DUPLIKASI: Jika ID P2TL (kolom index 1) sudah ada, lewati
                if (in_array($row[1], $existingIds)) {
                    $duplicateCount++;
                    continue;
                }

                $records[] = [
                    'no_p2tl'       => $row[0] ?? null,
                    'id_p2tl'       => $row[1] ?? null,
                    'idpel'         => $this->sanitizeIdpel($row[2]),
                    'update_status' => $row[3] ?? null,
                    'kwh_ts'        => (float)str_replace(',', '.', ($row[4] ?? 0)),
                    'waktu_periksa' => $this->parseDate($row[5], $row[12], $row[13], $row[14]),
                    'unit_ulp'      => $row[6] ?? null,
                    'no_agenda'     => $row[7] ?? null,
                    'username'      => $row[8] ?? null,
                    'nama_petugas'  => $row[9] ?? null,
                    'tarif'         => $row[10] ?? null,
                    'tegangan_r_n'  => $row[11] ?? null,
                    'tgl'           => $row[12] ?? null,
                    'bulan'         => $row[13] ?? null,
                    'tahun'         => $row[14] ?? null,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
                
                // Tambahkan ID baru ini ke list existing agar tidak duplikat di dalam satu file yang sama
                $existingIds[] = $row[1];
            }

            if (!empty($records)) {
                DB::table('p2tl_records')->insert($records);
                $inserted += count($records);
            }

            // Update Progress
            $currentProcessed = ($chunkIdx + 1) * $batchSize;
            $progress = min(95, (int)(($currentProcessed / $total) * 95));
            Cache::put('import_progress', $progress);

            unset($records); 
            unset($existingIds);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        DB::statement('SET UNIQUE_CHECKS=1');
       // ... (di akhir blok foreach chunk)

        DB::commit();
        $this->resetConfigs();
        
        // Pastikan progress mencapai 100%
        Cache::put('import_progress', 100);

        // 1. Buat kalimat yang lebih rapi
        if ($inserted > 0) {
            $pesan = "Berhasil mengimport " . number_format($inserted, 0, ',', '.') . " data baru.";
        } else {
            $pesan = "Tidak ada data baru yang ditambahkan.";
        }

        // 2. Tambahkan info duplikat jika ada
        if ($duplicateCount > 0) {
            $pesan .= " Sebanyak " . number_format($duplicateCount, 0, ',', '.') . " data duplikat dilewati.";
        }

        // 3. Gunakan with() untuk mengirim session success
        return redirect()->route('p2tl.index')->with('success', $pesan);

    } catch (\Exception $e) {
        DB::rollBack();
        // Kembalikan ke state awal jika gagal
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        DB::statement('SET UNIQUE_CHECKS=1');
        
        return redirect()->route('p2tl.index')->with('error', 'Gagal: ' . $e->getMessage());
    }
}

    private function resetConfigs()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        DB::statement('SET UNIQUE_CHECKS=1');
    }

    private function sanitizeIdpel($value)
    {
        // Menghapus karakter non-numerik (seperti spasi atau tanda petik dari Excel)
        return preg_replace('/[^0-9]/', '', (string)$value);
    }

  private function parseDate($value, $tgl, $bulan, $tahun)
{
    try {
        // 1. Coba bersihkan value utama
        $value = trim((string)$value);

        // 2. Jika WAKTU_PERIKSA berisi angka (Format Excel Serial)
        if (is_numeric($value) && $value > 40000) {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format('Y-m-d');
        }

        // 3. Jika WAKTU_PERIKSA berisi string tanggal yang valid
        if (!empty($value) && $value !== '-' && strlen($value) > 5) {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        }

        // 4. SOLUSI TERAKHIR: Jika kolom utama gagal/kosong, rakit dari kolom TGL, BLN, THN
        // Kita paksa format YYYY-MM-DD agar SQL mau menerima
        if (!empty($tgl) && !empty($bulan) && !empty($tahun)) {
            // Menghindari error jika tgl/bln hanya 1 digit (misal 2/1/2026 jadi 2026-01-02)
            return \Carbon\Carbon::createFromDate($tahun, $bulan, $tgl)->format('Y-m-d');
        }

        return null;
    } catch (\Exception $e) {
        // Jika semua gagal, coba rakit manual string-nya tanpa Carbon
        if (!empty($tgl) && !empty($bulan) && !empty($tahun)) {
            return $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-' . str_pad($tgl, 2, '0', STR_PAD_LEFT);
        }
        return null;
    }
}

// Tambahkan di dalam class P2tlController
public function awarding()
{
    // Mengambil data top 10 petugas dengan total kWh TS terbanyak
    $awards = \App\Models\P2tlRecord::select('nama_petugas', 'unit_ulp')
        ->selectRaw('SUM(kwh_ts) as total_kwh')
        ->selectRaw('COUNT(*) as total_pemeriksaan')
        ->groupBy('nama_petugas', 'unit_ulp')
        ->orderBy('total_kwh', 'desc')
        ->take(10)
        ->get();

    return view('p2tl.awarding', compact('awards'));
}

    private function syncKaryawan()
    {
        $names = DB::table('p2tl_records')
            ->whereNotNull('nama_petugas')
            ->distinct()
            ->pluck('nama_petugas')
            ->toArray();

        if (empty($names)) return;

        // Ambil nama yang sudah ada untuk menghindari duplikat
        $existing = Karyawan::whereIn('nama', $names)->pluck('nama')->toArray();
        $newNames = array_diff($names, $existing);

        if (!empty($newNames)) {
            $data = [];
            foreach ($newNames as $nama) {
                $data[] = [
                    'nama' => $nama,
                    'jabatan' => 'Petugas P2TL',
                    'tahun_masuk' => date('Y'),
                    'umur' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            // Insert per 500 karyawan (jika petugas sangat banyak)
            foreach (array_chunk($data, 500) as $chunk) {
                Karyawan::insert($chunk);
            }
        }
        
    }

   // Fungsi getProgress, cancel tetap sama...
    public function getProgress() { return response()->json(['progress' => Cache::get('import_progress', 0)]); }
    public function cancel() { Cache::put('import_cancelled', true); return response()->json(['status' => 'cancelled']); }

    // PEMBARUAN: Sesuaikan pesan sukses untuk notifikasi index
   public function truncate() 
{ 
    $jumlahData = P2tlRecord::count(); 

    // 2. Jika data sudah 0, kirim notifikasi error (merah)
    if ($jumlahData === 0) {
        return back()->with('error', 'Data rekapitulasi sudah kosong, tidak ada yang perlu dihapus.');
    }

    try {
        P2tlRecord::truncate(); 

        return back()->with('success', 'Seluruh data rekapitulasi berhasil dikosongkan!'); 
    } catch (\Exception $e) {
        // Antisipasi jika ada error database (misal: foreign key constraint)
        return back()->with('error', 'Gagal mengosongkan data: ' . $e->getMessage());
    }
}

    public function destroy($id) 
    { 
        P2tlRecord::findOrFail($id)->delete(); 
        return back()->with('success', 'Data berhasil dihapus dari sistem.'); 
    }

}