<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('slug')->after('name')->nullable();
            });

            // 2. Isi data slug untuk kategori yang sudah ada secara otomatis
            $categories = DB::table('categories')->get();
            foreach ($categories as $category) {
                DB::table('categories')
                    ->where('id', $category->id)
                    ->update(['slug' => Str::slug($category->name) . '-' . Str::random(5)]);
            }

            // 3. Sekarang baru ubah kolomnya menjadi unique dan tidak boleh null
            Schema::table('categories', function (Blueprint $table) {
                $table->string('slug')->unique()->change();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
