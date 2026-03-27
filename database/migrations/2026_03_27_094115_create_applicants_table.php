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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('profile_picture')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->unique();
            $table->text('resume')->nullable();
            $table->text('cover_letter')->nullable();
            $table->text('skills')->nullable();
            $table->text('experience')->nullable();
            $table->text('education')->nullable();
            $table->text('certifications')->nullable();
            $table->text('projects')->nullable();
            $table->text('languages')->nullable();
            $table->text('cv_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
