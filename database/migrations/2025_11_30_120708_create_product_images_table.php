<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('product_id');
            $table->string('image')->nullable();   // Image file name

            $table->boolean('isshown')->default(1);
            
            $table->unsignedBigInteger('admin_id')->nullable();

            $table->timestamps();

            // Foreign Keys
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admin')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
