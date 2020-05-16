<?php

namespace Modules\Accounts\Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Support\Str;
use Modules\Accounts\Entities\Customer;
use Illuminate\Support\Facades\Notification;
use Modules\Accounts\Entities\ResetPasswordCode;
use Modules\Accounts\Entities\ResetPasswordToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Accounts\Notifications\PasswordUpdatedNotification;
use Modules\Accounts\Notifications\SendForgetPasswordCodeNotification;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_send_password_reset_verification_code_to_user()
    {
        Notification::fake();

        $this->postJson(route('api.password.forget'))
            ->assertJsonValidationErrors(['username']);

        $this->postJson(route('api.password.forget'), [
            'username' => 'user@user.com',
        ])
            ->assertJsonValidationErrors(['username']);

        Notification::assertNothingSent();

        $user = factory(Customer::class)->create(['email' => 'user@user.com']);

        $this->postJson(route('api.password.forget'), [
            'username' => 'user@user.com',
        ])
            ->assertSuccessful()
            ->assertJsonStructure([
                'message',
                'links' => ['code'],
            ]);

        Notification::assertSentTo($user, SendForgetPasswordCodeNotification::class);
    }

    public function test_verification_token_can_be_retrieved()
    {
        factory(Customer::class)->create(['email' => 'user@user.com']);

        ResetPasswordCode::create([
            'username' => 'user@user.com',
            'code' => '123456',
        ]);

        $this->postJson(route('api.password.code'))
            ->assertJsonValidationErrors(['username', 'code']);

        $this->postJson(route('api.password.code'), [
            'username' => 'admin@user.com',
            'code' => 'invalid',
        ])
            ->assertJsonValidationErrors(['code']);

        $this->postJson(route('api.password.code'), [
            'username' => 'user@user.com',
            'code' => '123456',
        ])
            ->assertSuccessful()
            ->assertJsonStructure([
                'reset_token',
                'links' => ['reset'],
            ]);
    }

    public function test_password_can_be_updated_by_reset_token()
    {
        Notification::fake();

        $user = factory(Customer::class)->create(['email' => 'user@user.com']);

        ResetPasswordToken::create([
            'user_id' => $user->id,
            'token' => $token = Str::random(60),
        ]);

        $this->postJson(route('api.password.reset'))
            ->assertJsonValidationErrors(['password']);

        $this->postJson(route('api.password.reset'), [
            'token' => 'invalid',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ])
            ->assertJsonValidationErrors(['token']);

        Notification::assertNothingSent();

        $this->postJson(route('api.password.reset'), [
            'token' => $token,
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ])
            ->assertSuccessful()
            ->assertJson([
                'data' => $user->getResource()->jsonSerialize(),
            ])
            ->assertJsonStructure(['token']);

        // Now test login with new password.
        $this->postJson(route('sanctum.login'), [
            'username' => $user->email,
            'password' => '12345678',
            'device_name' => 'testing',
        ])
            ->assertSuccessful()
            ->assertJson([
                'data' => $user->getResource()->jsonSerialize(),
            ])
            ->assertJsonStructure(['token']);

        Notification::assertSentTo($user, PasswordUpdatedNotification::class);
    }
}
