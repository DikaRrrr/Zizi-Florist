<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model
{
    use HasFactory;

    protected $table = 'voucher';

    protected $fillable = [
        'kode',
        'tipe',
        'nilai',
        'minimal_pembelian',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active'
    ];
}
