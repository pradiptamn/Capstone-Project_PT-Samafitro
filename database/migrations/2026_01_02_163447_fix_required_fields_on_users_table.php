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
        Schema::table('users', function (Blueprint $table) {
            $table->string('ktp_number')->nullable()->change();
            $table->string('security_question')->nullable()->change();
            $table->string('security_answer')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ktp_number')->nullable(false)->change();
            $table->string('security_question')->nullable(false)->change();
            $table->string('security_answer')->nullable(false)->change();
        });
    }
};
