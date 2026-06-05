<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Import model P2tlRecord agar relasi terbaca dengan jelas
use App\Models\P2tlRecord; 

class Karyawan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'jabatan',
        'umur',
        'tahun_masuk',
        'foto',
    ];

    /**
     * Relasi ke data P2TL
     * Menghubungkan kolom 'nama' di tabel karyawan 
     * dengan kolom 'nama_petugas' di tabel p2tl_records
     */
    public function p2tlRecords()
    {
        return $this->hasMany(P2tlRecord::class, 'nama_petugas', 'nama');
    }

    /**
     * Boot function untuk menangani penghapusan foto secara otomatis
     * (Opsional tapi sangat berguna untuk Tugas Akhir agar storage tidak penuh)
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($karyawan) {
            if ($karyawan->foto) {
                $filePath = public_path('uploads/karyawan/' . $karyawan->foto);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        });
    }
}