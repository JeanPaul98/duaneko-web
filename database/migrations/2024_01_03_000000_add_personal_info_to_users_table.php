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
        Schema::table('users', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('phone_number');
            $table->string('gender')->nullable()->after('date_of_birth');
            $table->string('address')->nullable()->after('gender');
            $table->string('city')->nullable()->after('address');
            $table->string('district')->nullable()->after('city');
            $table->string('id_document_type')->nullable()->after('district');
            $table->string('id_document_number')->nullable()->after('id_document_type');
            $table->string('photo')->nullable()->after('id_document_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'gender',
                'address',
                'city',
                'district',
                'id_document_type',
                'id_document_number',
                'photo',
            ]);
        });
    }
};
