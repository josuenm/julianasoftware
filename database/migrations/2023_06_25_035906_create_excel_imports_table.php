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
        Schema::create('excel_imports', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('path')->nullable();
            $table->string('message')->nullable();
            $table->string('filename');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')->references('id')->on('user');
            $table->foreign('status_id')->references('id')->on('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excel_imports');
    }
};
