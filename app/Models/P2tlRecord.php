<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P2tlRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_p2tl',
        'id_p2tl',
        'idpel',
        'update_status',
        'kwh_ts',
        'waktu_periksa',
        'unit_ulp',
        'no_agenda',
        'username',
        'nama_petugas',
        'tarif',
        'tegangan_r_n',
        'tgl',
        'bulan',
        'tahun',
    ];
}