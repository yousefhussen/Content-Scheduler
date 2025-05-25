<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('message_content');
            $table->string('image_url')->nullable();
            $table->timestamp('scheduled_time')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'published'])->default('draft');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
