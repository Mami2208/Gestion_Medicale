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
        Schema::table('failed_login_attempts', function (Blueprint $table) {
            $table->string('email')->nullable()->after('ip_address');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('failed_login_attempts', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
}; 