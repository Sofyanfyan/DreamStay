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
        Schema::create('kos_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kos_id');
            $table->foreign('kos_id')->references('id')->on('kos')->cascadeOnDelete();
            $table->unsignedBigInteger('detail_id');
            $table->foreign('detail_id')->references('id')->on('details')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kos_details');
    }
};