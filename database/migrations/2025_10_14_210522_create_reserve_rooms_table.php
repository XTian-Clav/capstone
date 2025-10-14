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
        Schema::create('reserve_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('reserved_by');
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->string('status');
            $table->string('office');
            $table->string('contact');
            $table->string('email');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('accept_terms')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserve_rooms');
    }
};
