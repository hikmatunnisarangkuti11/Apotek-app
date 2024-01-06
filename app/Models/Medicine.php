<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    // property yang digunakan untuk menyimpan nama-nama column yang bisa di isi valuenya
    use HasFactory;
    protected $fillable = [
        'type',
        'name',
        'price',
        'stock',
    ];

    // penegasan tipe data dari migration (hasil property ini ketika diambil atau di insert/update dibuat dalam tipe data apa)
    protected $casts = [
        'medicines' => 'array'
    ];
}
