<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{

    protected $table = 'produk';

    protected $fillable = [
        'nama_produk',
        'slug',
        'deskripsi',
        'harga',
        'stok',
        'terjual',
        'foto'
    ];

    public function rating()
    {
        return $this->hasMany(Rating::class);
    }
}
