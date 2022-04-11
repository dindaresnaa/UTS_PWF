<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Mendifinisikan tabel products di database
    protected $table = 'products';

    // Mempersilahkan user untuk menginputkan langsung ke database
    protected $fillable = ['name', 'price', 'type', 'expired_at'];
}
