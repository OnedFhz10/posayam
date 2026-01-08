<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $guarded = ['id'];

    // Relasi ke Detail Item
    public function details()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    public function user()
    {
        // Ini memberitahu Laravel bahwa 1 Transaksi milik 1 User
        return $this->belongsTo(User::class);
    }
    
    // Relasi ke Cabang
    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    // Relasi ke Kasir
    public function kasir()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    
}
