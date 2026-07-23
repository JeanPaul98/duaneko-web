<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->foreignId('country_id')->nullable()->after('district')->constrained()->nullOnDelete();
            $table->foreignId('administrative_division_id')->nullable()->after('country_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('administrative_division_id');
            $table->dropConstrainedForeignId('country_id');
        });
    }
};
