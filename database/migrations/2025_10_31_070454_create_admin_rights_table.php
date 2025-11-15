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
        Schema::create('admin_rights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->unsigned()->nullable();
            $table->unsignedBigInteger('page_id')->unsigned()->nullable();
            $table->timestamps();

            // Foreign key constraint for admin_id
            $table->foreign('admin_id')->references('id')->on('admin')->onDelete('set null');

            // Foreign key constraint for page_id
            $table->foreign('page_id')->references('id')->on('admin_pages')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_rights');
    }
};
