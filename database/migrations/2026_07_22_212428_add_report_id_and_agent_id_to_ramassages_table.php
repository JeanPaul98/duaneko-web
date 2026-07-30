<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ramassages', function (Blueprint $table) {
            $table->foreignId('report_id')->nullable()->after('id')->constrained('reports')->nullOnDelete();
            $table->foreignId('agent_id')->nullable()->after('company_id')->constrained('users')->nullOnDelete();
        });

        // Un ramassage n'a désormais qu'un seul agent : on reprend le premier agent
        // déjà rattaché via l'ancienne table pivot ramassages_agents.
        DB::table('ramassages_agents')
            ->select('ramassage_id', DB::raw('MIN(agent_id) as agent_id'))
            ->groupBy('ramassage_id')
            ->get()
            ->each(function ($row) {
                DB::table('ramassages')->where('id', $row->ramassage_id)->update(['agent_id' => $row->agent_id]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ramassages', function (Blueprint $table) {
            $table->dropConstrainedForeignId('report_id');
            $table->dropConstrainedForeignId('agent_id');
        });
    }
};
