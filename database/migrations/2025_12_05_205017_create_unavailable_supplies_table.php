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
        Schema::create('unavailable_supplies', function (Blueprint $table) {
            $table->id();
            $table->string('picture')->nullable();
            $table->foreignId('supply_id')->constrained('supplies')->cascadeOnDelete();
            $table->unsignedInteger('unavailable_quantity')->default(1);
            $table->string('status');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unavailable_supplies');
    }
};
