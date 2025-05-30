<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waiting extends Model
{
    protected $table="waitinglist"; // Inisiasi Nama Table
    protected $guarded = ['id'];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
