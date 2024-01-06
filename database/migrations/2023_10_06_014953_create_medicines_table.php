<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id(); //primary key auto increments
            $table->enum('type', ['tablet', 'sirup', 'kapsul']); //type data enum hanya bisa di isi 3 nilai yang sudah ditentukan
            $table->string('name'); //('name') ambil dari coloum
            $table->integer('price');    
            $table->integer('stock');    
            $table->timestamps(); //akan menghasilkan dua colum, created_at (auto terisi tgl pembuatan data), update_at (auto terisi tgl data diubah)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
