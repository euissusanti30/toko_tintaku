<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $fillable = [
        'kategori_id',
        'nama_produk',
        'harga',
        'stok',
        'berat',
        'detail',
        'foto',
        'status'
    ];

    /*
    |--------------------------------
    | RELASI KATEGORI
    |--------------------------------
    */

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}