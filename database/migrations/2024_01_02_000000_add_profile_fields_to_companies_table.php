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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('type')->default('mairie')->after('name');
            $table->string('logo')->nullable()->after('type');
            $table->text('description')->nullable()->after('logo');
            $table->string('address')->nullable()->after('description');
            $table->string('city')->nullable()->after('address');
            $table->string('district')->nullable()->after('city');
            $table->string('phone_number')->nullable()->after('district');
            $table->string('email')->nullable()->after('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['type', 'logo', 'description', 'address', 'city', 'district', 'phone_number', 'email']);
        });
    }
};
