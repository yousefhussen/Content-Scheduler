<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('platforms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['twitter', 'instagram', 'linkedin', 'facebook', 'other']);
            $table->timestamps();
        });

        // Insert predefined platform data
        DB::table('platforms')->insert([
            ['name' => 'Twitter', 'type' => 'twitter', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Instagram', 'type' => 'instagram', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'LinkedIn', 'type' => 'linkedin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Facebook', 'type' => 'facebook', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('platforms');
    }
};
