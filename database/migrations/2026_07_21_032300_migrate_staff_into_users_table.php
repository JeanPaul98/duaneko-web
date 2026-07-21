<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (['admin', 'manager', 'agent', 'citizen'] as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Citoyens déjà présents dans users (app mobile) : rôle citizen.
        User::whereDoesntHave('roles')->each(function (User $user) {
            $user->assignRole('citizen');
        });

        foreach (DB::table('admins')->get() as $admin) {
            $parts = preg_split('/\s+/', trim($admin->name), 2);

            $id = DB::table('users')->insertGetId([
                'first_name' => $parts[0] ?? $admin->name,
                'last_name' => $parts[1] ?? '',
                'email' => $admin->email,
                'password' => $admin->password,
                'status' => 'validated',
                'created_at' => $admin->created_at,
                'updated_at' => $admin->updated_at,
            ]);

            User::find($id)->assignRole('admin');
        }

        $managerIdMap = [];
        foreach (DB::table('managers')->get() as $manager) {
            $id = DB::table('users')->insertGetId([
                'first_name' => $manager->first_name,
                'last_name' => $manager->last_name,
                'phone_number' => $manager->phone_number,
                'email' => $manager->email,
                'password' => $manager->password,
                'company_id' => $manager->company_id,
                'status' => $manager->status,
                'created_at' => $manager->created_at,
                'updated_at' => $manager->updated_at,
            ]);

            User::find($id)->assignRole('manager');
            $managerIdMap[$manager->id] = $id;
        }

        $agentIdMap = [];
        foreach (DB::table('agents')->get() as $agent) {
            $id = DB::table('users')->insertGetId([
                'first_name' => $agent->first_name,
                'last_name' => $agent->last_name,
                'phone_number' => $agent->phone_number,
                'email' => $agent->email,
                'password' => $agent->password,
                'company_id' => $agent->company_id,
                'status' => $agent->status,
                'created_at' => $agent->created_at,
                'updated_at' => $agent->updated_at,
            ]);

            User::find($id)->assignRole('agent');
            $agentIdMap[$agent->id] = $id;
        }

        Schema::table('ramassages_agents', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
        });

        foreach ($agentIdMap as $oldId => $newId) {
            DB::table('ramassages_agents')->where('agent_id', $oldId)->update(['agent_id' => $newId]);
        }

        Schema::table('ramassages_agents', function (Blueprint $table) {
            $table->foreign('agent_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::dropIfExists('agents');
        Schema::dropIfExists('managers');
        Schema::dropIfExists('admins');
    }

    /**
     * Reverse the migrations.
     *
     * Migration de données à sens unique : la structure est recréée vide,
     * les données ne sont pas redistribuées depuis `users`.
     */
    public function down(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('managers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_number')->unique();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->string('status')->default('validated');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_number')->unique();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->string('status')->default('validated');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('ramassages_agents', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
        });
    }
};
