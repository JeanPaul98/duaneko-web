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
        Schema::create('ramassages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique()->default('');
            $table->double('latitude');
            $table->double('longitude');
            $table->date('date_de_ramassage');
            $table->time('heure_de_ramassage');
            $table->string('description');
            $table->foreignId('company_id')->nullable()->constrained('companies');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ramassages');
    }
};
