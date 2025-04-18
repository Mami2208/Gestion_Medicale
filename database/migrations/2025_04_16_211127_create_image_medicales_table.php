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
        Schema::create('image_medicales', function (Blueprint $table) {
            $table->id();
            $table->string('urlAccess')->nullable();
            $table->string('typeImage')->nullable();
            $table->date('dateCreation')->nullable();
            $table->boolean('isDicom')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_medicales');
    }
};
