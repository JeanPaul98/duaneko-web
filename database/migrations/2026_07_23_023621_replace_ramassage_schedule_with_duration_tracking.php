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
        Schema::table('ramassages', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable()->after('agent_id');
            $table->dropColumn(['date_de_ramassage', 'heure_de_ramassage']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ramassages', function (Blueprint $table) {
            $table->date('date_de_ramassage')->nullable();
            $table->time('heure_de_ramassage')->nullable();
            $table->dropColumn('completed_at');
        });
    }
};
