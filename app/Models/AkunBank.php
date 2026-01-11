<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AkunBank extends Model
{
    use HasFactory;

    protected $table = 'akun_bank';

    protected $fillable = [
        'bank', 
        'no_rekening', 
        'atas_nama', 
        'is_active'
    ];
}
