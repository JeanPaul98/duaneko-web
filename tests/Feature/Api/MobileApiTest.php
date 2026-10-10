<?php

namespace Tests\Feature\Api;

use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MobileApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(array $attributes = []): User
    {
        static $n = 0;
        $n++;

        return User::create(array_merge([
            'first_name' => 'Ama',
            'last_name' => 'Koffi',
            'email' => "user{$n}@example.com",
            'phone_number' => "9000000{$n}",
            'password' => 'secret123',
        ], $attributes));
    }

    private function bearer(User $user): array
    {
        return ['Authorization' => 'Bearer ' . auth('api')->login($user), 'Accept' => 'application/json'];
    }

    private function makeReport(array $attributes = []): Report
    {
        return Report::create(array_merge([
            'image' => 'x.jpg',
            'type' => 'wild_dumps',
            'latitude' => 6.13,
            'longitude' => 1.22,
            'status' => 'pending',
            'description' => 'test',
        ], $attributes));
    }

    public function test_public_report_route_reaches_public_store_without_user(): void
    {
        Storage::fake('public');
        $user = $this->makeUser();

        $this->withHeaders($this->bearer($user))
            ->post('/api/auth/reports/public', ['image' => UploadedFile::fake()->image('a.jpg'), 'type' => 'wild_dumps', 'latitude' => 6.13, 'longitude' => 1.22, 'description' => 'test'])
            ->assertCreated()
            ->assertJsonPath('report.user_id', null);
    }

    public function test_authenticated_report_is_attributed_to_token_owner(): void
    {
        Storage::fake('public');
        $user = $this->makeUser();

        $this->withHeaders($this->bearer($user))
            ->post("/api/auth/reports/{$user->id}", ['image' => UploadedFile::fake()->image('a.jpg'), 'type' => 'wild_dumps', 'latitude' => 6.13, 'longitude' => 1.22, 'description' => 'test'])
            ->assertCreated()
            ->assertJsonPath('report.user_id', $user->id);
    }

    public function test_report_without_position_is_rejected_with_422(): void
    {
        Storage::fake('public');

        $this->post('/api/auth/reports/public', ['image' => UploadedFile::fake()->image('a.jpg'), 'description' => 'test'], ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['latitude', 'longitude']);
    }

    public function test_report_with_empty_type_and_description_uses_defaults(): void
    {
        Storage::fake('public');

        $this->post('/api/auth/reports/public', ['image' => UploadedFile::fake()->image('a.jpg'), 'type' => '', 'description' => '', 'latitude' => 6.13, 'longitude' => 1.22], ['Accept' => 'application/json'])
            ->assertCreated()
            ->assertJsonPath('report.type', 'wild_dumps');
    }

    public function test_authenticated_report_route_requires_token(): void
    {
        Storage::fake('public');

        $this->post('/api/auth/reports/1', ['image' => UploadedFile::fake()->image('a.jpg'), 'latitude' => 6.13, 'longitude' => 1.22], ['Accept' => 'application/json'])
            ->assertUnauthorized();
    }

    public function test_my_reports_returns_only_own_reports_paginated(): void
    {
        $me = $this->makeUser();
        $other = $this->makeUser();
        $this->makeReport(['user_id' => $me->id, 'status' => 'done']);
        $this->makeReport(['user_id' => $me->id]);
        $this->makeReport(['user_id' => $other->id]);

        $this->withHeaders($this->bearer($me))->getJson('/api/auth/reports/mine')
            ->assertOk()
            ->assertJsonPath('reports.total', 2)
            ->assertJsonCount(2, 'reports.data');

        $this->withHeaders($this->bearer($me))->getJson('/api/auth/reports/mine?status=done')
            ->assertOk()
            ->assertJsonPath('reports.total', 1);
    }

    public function test_stats_counts_global_and_own_reports(): void
    {
        $me = $this->makeUser();
        $other = $this->makeUser();
        $this->makeReport(['user_id' => $me->id, 'status' => 'done']);
        $this->makeReport(['user_id' => $me->id]);
        $this->makeReport(['user_id' => $other->id, 'status' => 'done']);
        $this->makeReport();

        $this->withHeaders($this->bearer($me))->getJson('/api/auth/reports/stats')
            ->assertOk()
            ->assertExactJson(['stats' => ['total' => 4, 'done' => 2, 'mine' => 2, 'mine_done' => 1]]);
    }

    public function test_mine_and_stats_require_authentication(): void
    {
        $this->getJson('/api/auth/reports/mine')->assertUnauthorized();
        $this->getJson('/api/auth/reports/stats')->assertUnauthorized();
        $this->putJson('/api/auth/me', ['first_name' => 'Kofi'])->assertUnauthorized();
        $this->putJson('/api/auth/me/password', [])->assertUnauthorized();
    }

    public function test_update_me_changes_only_sent_fields_and_rejects_taken_phone(): void
    {
        $me = $this->makeUser();
        $other = $this->makeUser();

        $this->withHeaders($this->bearer($me))->putJson('/api/auth/me', ['first_name' => 'Kofi'])
            ->assertOk()
            ->assertJsonPath('user.first_name', 'Kofi')
            ->assertJsonPath('user.phone_number', $me->phone_number);

        $this->withHeaders($this->bearer($me))->putJson('/api/auth/me', ['phone_number' => $other->phone_number])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('phone_number');
    }

    public function test_update_me_ignores_non_profile_fields(): void
    {
        $me = $this->makeUser(['status' => 'pending']);

        $this->withHeaders($this->bearer($me))->putJson('/api/auth/me', ['status' => 'validated', 'company_id' => 1])
            ->assertOk();

        $this->assertSame('pending', $me->fresh()->status);
        $this->assertNull($me->fresh()->company_id);
    }

    public function test_update_password_requires_current_password(): void
    {
        $me = $this->makeUser();

        $this->withHeaders($this->bearer($me))->putJson('/api/auth/me/password', [
            'current_password' => 'wrong',
            'password' => 'newpass1',
            'password_confirmation' => 'newpass1',
        ])->assertUnprocessable();

        $this->withHeaders($this->bearer($me))->putJson('/api/auth/me/password', [
            'current_password' => 'secret123',
            'password' => 'newpass1',
            'password_confirmation' => 'newpass1',
        ])->assertOk();

        $this->assertTrue(Hash::check('newpass1', $me->fresh()->password));
    }
}
