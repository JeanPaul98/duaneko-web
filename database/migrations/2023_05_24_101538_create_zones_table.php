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
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique()->default('');
            $table->string('google_map_name');
            $table->double('northeast_latitude');
            $table->double('northeast_longitude');
            $table->double('southwest_latitude');
            $table->double('southwest_longitude');
            $table->string('color');
            $table->foreignId('company_id')->nullable()->constrained('companies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zones');
    }
};
