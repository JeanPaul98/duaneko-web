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
        Schema::table('managers', function (Blueprint $table) {
            $table->string('status')->default('validated')->after('company_id');
        });

        Schema::table('agents', function (Blueprint $table) {
            $table->string('status')->default('validated')->after('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('managers', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
