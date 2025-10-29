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
        Schema::create('mentors', function (Blueprint $table) {
            $table->id();
            $table->string('avatar')->nullable();
            $table->string('name')->unique();
            $table->string('contact')->unique();
            $table->string('email')->unique();
            $table->string('expertise');
            $table->text('personal_info');
            $table->timestamps();
            $table->softDeletes();
        });

        // Pivot table for mentor-startup assignments
        Schema::create('mentor_startup', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')->constrained('mentors')->cascadeOnDelete();
            $table->foreignId('startup_id')->constrained('startups')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentor_startup');
        Schema::dropIfExists('mentors');
    }
};