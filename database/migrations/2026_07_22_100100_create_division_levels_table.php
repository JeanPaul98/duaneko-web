<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('division_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedTinyInteger('depth');
            $table->timestamps();

            $table->unique(['country_id', 'depth']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('division_levels');
    }
};
