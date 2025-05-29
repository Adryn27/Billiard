<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waiting extends Model
{
    protected $table="waitinglist"; // Inisiasi Nama Table
    protected $guarded = ['id'];
}
