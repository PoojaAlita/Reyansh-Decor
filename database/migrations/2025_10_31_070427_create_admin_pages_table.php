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
        Schema::create('admin_pages', function (Blueprint $table) {
            $table->id();
            $table->text('title')->nullable();
            $table->text('url')->nullable();
            $table->text('icon')->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('sortorder')->unsigned()->nullable();
            $table->smallInteger('isshown')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_pages');
    }
};
