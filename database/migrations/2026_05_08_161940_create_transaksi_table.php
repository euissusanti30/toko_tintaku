<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {

            $table->id();

            $table->string('nama_customer');

            $table->string('email');

            $table->string('telepon');

            $table->text('alamat');

            $table->integer('total_harga');

            $table->string('status')
                ->default('Menunggu Pembayaran');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};