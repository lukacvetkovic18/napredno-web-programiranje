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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); // Primarni ključ
            $table->string('naziv_rada'); // Naziv rada
            $table->string('naziv_rada_engleski'); // Naziv rada na engleskom
            $table->text('zadatak_rada'); // Zadatak rada
            $table->enum('tip_studija', ['stručni', 'preddiplomski', 'diplomski']); // Tip studija
            $table->unsignedBigInteger('user_id'); // ID nastavnika
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps(); // Kreira created_at i updated_at kolone
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
