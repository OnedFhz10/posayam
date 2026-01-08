<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    protected $guarded = [];
    protected $table = 'transaksi_details';

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}