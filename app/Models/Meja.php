<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    protected $table="meja"; // Inisiasi Nama Table
    protected $guarded = ['id'];

    public function kategori()
    {
        return $this->belongsTo(kategori::class);
    }
    public function reservasi()
    {
        return $this->hasOne(Reservasi::class);
    }
}
