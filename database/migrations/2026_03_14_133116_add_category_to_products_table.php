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
        if(Schema::hasColumn('products', 'category')){
            Schema::table('products', function (Blueprint $table) {
            $table->enum('category', [
                'analgesicos',
                'antialergicos',
                'vitaminas',
                'higiene',
                'dermatologia',
                'infantil'
            ])->nullable();
        }
        );}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
