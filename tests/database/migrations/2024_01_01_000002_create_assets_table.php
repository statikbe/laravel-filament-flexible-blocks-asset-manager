<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->json('name')->nullable();
            $table->json('custom_file_name')->nullable();
            $table->boolean('use_custom_file_name')->default(false);
            $table->timestamps();
        });
    }
};
