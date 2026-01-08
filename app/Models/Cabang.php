<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    protected $guarded = ['id']; // Semua kolom boleh diisi kecuali ID

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
