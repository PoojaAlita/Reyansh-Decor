<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->index();
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->string('material')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->integer('stock')->nullable();
            $table->tinyInteger('isshown')->default(1);
            $table->unsignedBigInteger('admin_id')->nullable()->index();
            $table->timestamps();

            // Foreign keys (optional - uncomment if you have these tables)
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admin')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
}
