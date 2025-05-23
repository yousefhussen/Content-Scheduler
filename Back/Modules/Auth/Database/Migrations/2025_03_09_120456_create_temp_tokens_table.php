<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('temp_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('expires_at');
            $table->timestamps();

            // Indexes for faster lookups
            $table->index('token');
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('temp_tokens');
    }
};
