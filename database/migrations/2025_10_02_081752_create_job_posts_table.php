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
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employer_id');
            $table->string('title');
            $table->text('description');
            $table->json('location');
            $table->json('category');
            $table->json('skills')->nullable();
            $table->string('salary_range')->nullable();
            $table->enum('job_type', ['full-time', 'part-time', 'internship', 'contract'])->default('full-time');
            $table->enum('status', ['draft','active','closed'])->default('active');
            $table->timestamps();

            $table->foreign('employer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
