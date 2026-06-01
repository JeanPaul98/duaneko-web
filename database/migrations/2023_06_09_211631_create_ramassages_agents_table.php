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
        Schema::create('ramassages_agents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('ramassage_id')->unsigned();
            $table->unsignedBiginteger('agent_id')->unsigned();

            $table->foreign('ramassage_id')->references('id')
                 ->on('ramassages')->onDelete('cascade');
            $table->foreign('agent_id')->references('id')
                ->on('agents')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ramassages_agents');
    }
};
