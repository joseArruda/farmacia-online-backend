<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("description");
            $table->string("category", 30);
            $table->integer("stock")->default(0);
            $table->decimal("price",10,2);
            $table->string('image')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
