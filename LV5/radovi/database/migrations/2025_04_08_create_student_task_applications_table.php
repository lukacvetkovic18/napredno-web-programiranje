<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_task_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id'); // Reference to user ID
            $table->unsignedBigInteger('task_id');   // Reference to task ID
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_task_applications');
    }
};
