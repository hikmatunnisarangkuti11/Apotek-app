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
            $table->id(); 
            $table->enum('type', ['tablet', 'syrup', 'capsule']); // tipe data enum hanya bisa di isi 3 nilai yang sudah di tentukan
            $table->string('name'); 
            $table->integer('price'); 
            $table->integer('stock'); 
            $table->timestamps(); // akan menghasilkan 2 column, created_at (auto terisi tgl pembuatan data), update at (auto terisi tgl data diubah)
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
