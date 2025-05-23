<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('item_id')
                ->constrained('items')
                ->onDelete('cascade');
            $table->timestamps(); // Includes created_at and updated_at

            // Optional: Unique constraint to prevent duplicate entries
            $table->unique(['user_id', 'item_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('wishlists');
    }
};
