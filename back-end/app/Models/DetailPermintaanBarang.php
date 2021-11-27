<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPermintaanBarang extends Model
{
    use HasFactory;

/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_permintaan_barang', 'kode_barang', 'qty', 'keterangan',
    ];
}
