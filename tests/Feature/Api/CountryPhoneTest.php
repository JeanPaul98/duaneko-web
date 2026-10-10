<?php

namespace Tests\Feature\Api;

use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CountryPhoneTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Country::create(['name' => 'Togo', 'iso_code' => 'TG', 'phone_code' => '+228', 'is_active' => true]);
        Country::create(['name' => 'Bénin', 'iso_code' => 'BJ', 'phone_code' => '+229', 'is_active' => false]);
        Country::create(['name' => 'Sans indicatif', 'iso_code' => 'XX', 'phone_code' => null, 'is_active' => true]);
    }

    private function registerPayload(string $phone): array
    {
        return [
            'first_name' => 'Ama',
            'last_name' => 'Koffi',
            'phone_number' => $phone,
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];
    }

    public function test_countries_lists_only_active_countries_with_phone_code(): void
    {
        $this->getJson('/api/countries')
            ->assertOk()
            ->assertExactJson(['countries' => [
                ['name' => 'Togo', 'iso_code' => 'TG', 'phone_code' => '+228'],
            ]]);
    }

    public function test_register_requires_international_format(): void
    {
        $this->postJson('/api/auth/register', $this->registerPayload('90000000'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('phone_number');

        $this->postJson('/api/auth/register', $this->registerPayload('+22890000000'))
            ->assertCreated()
            ->assertJsonPath('user.phone_number', '+22890000000');
    }

    public function test_login_with_full_number(): void
    {
        $this->postJson('/api/auth/register', $this->registerPayload('+22890000000'))->assertCreated();

        $this->postJson('/api/auth/login', ['phone_number' => '+22890000000', 'password' => 'secret123'])
            ->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_legacy_account_without_country_code_can_still_log_in(): void
    {
        User::create(['first_name' => 'A', 'last_name' => 'B', 'phone_number' => '90000000', 'password' => 'secret123']);

        $this->postJson('/api/auth/login', ['phone_number' => '+22890000000', 'password' => 'secret123'])
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->postJson('/api/auth/login', ['phone_number' => '+22890000000', 'password' => 'wrong12'])
            ->assertUnauthorized();
    }
}
