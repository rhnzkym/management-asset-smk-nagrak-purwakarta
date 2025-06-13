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
        Schema::create('item_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained(); // optional kalau via room_loan
            $table->foreignId('room_loan_id')->nullable()->constrained();
            $table->foreignId('item_id')->constrained();
            $table->integer('jumlah');
            $table->string('status')->default('dipinjam');
            $table->datetime('tanggal_pinjam');
            $table->datetime('tanggal_kembali')->nullable();
            $table->string('photo')->nullable(); // Photo saat peminjaman
            $table->string('return_photo')->nullable(); // Photo saat pengembalian
            $table->integer('good')->nullable(); // Jumlah barang yang dikembalikan dalam kondisi baik
            $table->integer('broken')->nullable(); // Jumlah barang yang dikembalikan dalam kondisi rusak
            $table->integer('lost')->nullable(); // Jumlah barang yang hilang
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_loans');
    }
};
