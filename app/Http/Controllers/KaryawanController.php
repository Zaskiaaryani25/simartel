<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\P2tlRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{
    /**
     * TAMPILAN HALAMAN UTAMA (Load dari Cache Database)
     */
    public function index()
    {
        // Menggunakan join agar loading halaman instan (tidak menghitung ulang)
        $karyawans = Karyawan::leftJoin('karyawan_stats', 'karyawans.id', '=', 'karyawan_stats.karyawan_id')
            ->select([
                'karyawans.*',
                'karyawan_stats.total_periksa',
                'karyawan_stats.hit_rate',
                'karyawan_stats.rincian_temuan',
                'karyawan_stats.total_akumulasi_bh',
                'karyawan_stats.total_akumulasi_kwh',
            ])
            ->get();

        return view('karyawan.index', compact('karyawans'));
    }

    /**
     * SINKRONISASI DATA (Proses Hitung & Simpan ke Database Stats)
     */
   public function syncFromP2tl()
{
    // 1. Ambil data P2TL dan kelompokkan berdasarkan nama petugas (Normalisasi Huruf Besar)
    $allRecords = P2tlRecord::select('nama_petugas', 'update_status', 'kwh_ts')->get()
        ->groupBy(function($item) {
            return trim(strtoupper($item->nama_petugas));
        });

    // 2. OTOMATIS DAFTARKAN PETUGAS BARU
    // Kita ambil semua nama unik dari P2TL, jika belum ada di tabel Karyawan, kita buatkan.
    foreach ($allRecords as $namaPetugas => $records) {
        // Cari berdasarkan nama (case-insensitive)
        $exists = Karyawan::whereRaw('UPPER(TRIM(nama)) = ?', [$namaPetugas])->exists();
        
        if (!$exists && !empty($namaPetugas)) {
            Karyawan::create([
                'nama' => $namaPetugas,
                'jabatan' => 'Petugas P2TL', // Jabatan default
                'umur' => 0,               // Default
                'tahun_masuk' => date('Y'), // Tahun sekarang
                'foto' => null
            ]);
        }
    }

    // 3. Ambil ulang semua karyawan (termasuk yang baru saja didaftarkan)
    $karyawans = Karyawan::all();

    DB::transaction(function () use ($allRecords, $karyawans) {
        foreach ($karyawans as $k) {
            $namaKey = trim(strtoupper($k->nama));
            $myRecords = $allRecords->get($namaKey, collect());
            
            $categories = [
                'PERIKSA - SESUAI' => 'SESUAI',
                'TEMUAN - K2'     => 'K2',
                'TEMUAN - P1'     => 'P1',
                'TEMUAN - P2'     => 'P2',
                'TEMUAN - P3'     => 'P3',
                'TEMUAN - P4'     => 'P4',
            ];

            $rincian = [];
            $totalBh = 0;
            $totalKwh = 0;

            foreach ($categories as $label => $keyword) {
                $filtered = $myRecords->filter(function($r) use ($keyword) {
                    return str_contains(strtoupper($r->update_status ?? ''), $keyword);
                });

                $count = $filtered->count();
                $sumKwh = $filtered->sum('kwh_ts');

                $rincian[$label] = [
                    'bh' => $count,
                    'kwh' => $sumKwh
                ];
                
                if ($keyword !== 'SESUAI') {
                    $totalBh += $count;
                    $totalKwh += $sumKwh;
                }
            }

            // 4. Update atau Insert ke tabel Cache (karyawan_stats)
            DB::table('karyawan_stats')->updateOrInsert(
                ['karyawan_id' => $k->id],
                [
                    'total_periksa' => $myRecords->count(),
                    'hit_rate' => $myRecords->count() > 0 ? round(($totalBh / $myRecords->count()) * 100, 2) : 0,
                    'rincian_temuan' => json_encode($rincian),
                    'total_akumulasi_bh' => $totalBh,
                    'total_akumulasi_kwh' => $totalKwh,
                    'updated_at' => now(),
                ]
            );
        }
    });

    return redirect()->back()->with('success', 'Sinkronisasi Berhasil! Data Petugas telah didaftarkan secara otomatis.');
}

    /**
     * FORM TAMBAH KARYAWAN
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required', 
            'jabatan' => 'required', 
            'umur' => 'required|integer', 
            'tahun_masuk' => 'required|integer', 
            'foto' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            $nama_foto = time() . '_' . $request->file('foto')->getClientOriginalName();
            $request->file('foto')->move(public_path('uploads/karyawan'), $nama_foto);
            $data['foto'] = $nama_foto;
        }

        Karyawan::create($data);
        return redirect()->route('karyawan.index')->with('success', 'Data Personel Berhasil Ditambahkan!');
    }

    public function edit(Karyawan $karyawan)
    {
        return view('karyawan.edit', compact('karyawan'));
    }

    /**
     * PROSES UPDATE DATA
     */
    public function update(Request $request, Karyawan $karyawan)
    {
        $data = $request->validate([
            'nama' => 'required', 
            'jabatan' => 'required', 
            'umur' => 'required|integer', 
            'tahun_masuk' => 'required|integer', 
            'foto' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            if ($karyawan->foto && File::exists(public_path('uploads/karyawan/'.$karyawan->foto))) {
                File::delete(public_path('uploads/karyawan/'.$karyawan->foto));
            }
            $nama_foto = time() . '_' . $request->file('foto')->getClientOriginalName();
            $request->file('foto')->move(public_path('uploads/karyawan'), $nama_foto);
            $data['foto'] = $nama_foto;
        }

        $karyawan->update($data);
        return redirect()->route('karyawan.index')->with('success', 'Data Personel Berhasil Diperbarui!');
    }

    /**
     * HAPUS SATU DATA (Sekaligus bersihkan cache stats)
     */
    public function destroy(Karyawan $karyawan)
    {
        if ($karyawan->foto && File::exists(public_path('uploads/karyawan/'.$karyawan->foto))) {
            File::delete(public_path('uploads/karyawan/'.$karyawan->foto));
        }

        // Hapus data stat terkait secara manual jika tidak menggunakan ON DELETE CASCADE
        DB::table('karyawan_stats')->where('karyawan_id', $karyawan->id)->delete();
        
        $karyawan->delete();

        if (Karyawan::count() == 0) {
            DB::statement('ALTER TABLE karyawans AUTO_INCREMENT = 1');
        }

        return redirect()->back()->with('success', 'Data Personel Dihapus!');
    }

    /**
     * KOSONGKAN SEMUA DATA
     */
   public function truncate()
{
    // 1. Cek apakah ada data karyawan
    $count = Karyawan::count();

    // 2. Jika sudah kosong, langsung kembalikan notif error
    if ($count === 0) {
        return redirect()->route('karyawan.index')->with('error', 'Data sudah kosong, tidak ada yang perlu dihapus.');
    }

    // 3. Proses hapus file foto (Gunakan chunk agar hemat memori jika data ribuan)
    Karyawan::whereNotNull('foto')->chunk(100, function ($karyawans) {
        foreach ($karyawans as $k) {
            $filePath = public_path('uploads/karyawan/' . $k->foto);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }
    });

    try {
        // 4. Proses Truncate Tabel
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DB::table('karyawan_stats')->truncate(); 
        Karyawan::truncate(); 
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return redirect()->route('karyawan.index')->with('success', 'Seluruh data petugas telah dibersihkan!');
        
    } catch (\Exception $e) {
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return redirect()->route('karyawan.index')->with('error', 'Gagal membersihkan data: ' . $e->getMessage());
    }
}
}