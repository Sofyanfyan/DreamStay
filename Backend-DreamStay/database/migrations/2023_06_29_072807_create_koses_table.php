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
        Schema::create('kos', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->integer('biaya');
            $table->boolean('termasuk_listrik');
            $table->string('fasilitas_kamar');
            $table->string('fasilitas_kamar_mandi');
            $table->string('fasilitas_dapur');
            $table->integer('lantai');
            $table->integer('lebar');
            $table->integer('panjang');
            $table->text('foto_kamar');
            $table->text('foto_kamar1')->nullable();
            $table->text('foto_kamar_mandi');
            $table->text('foto_dapur')->nullable();
            $table->string('whatsapp_owner');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unsignedBigInteger('book_id')->nullable();
            $table->foreign('book_id')->references('id')->on('books')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('kos');
    }
};