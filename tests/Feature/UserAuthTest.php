<?php

namespace Tests\Feature\User\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Auth;
use App\Models\User;
use Tests\ResponseWriter;

class UserAuthTest extends TestCase
{
    use DatabaseTransactions;

    const TEST_EMAIL = 'test@test.rru';
    const TEST_PASSWORD = 'secret';
    const TEST_WRONG_PASSWORD = 'other';
    const TEST_FIRST_NAME = 'Grzegorz';
    const TEST_LAST_NAME = 'Brzęczyszczykiewicz';

    protected $authenticate = false;

    public function testUserLogin()
    {
        $user = factory(User::class)->create();

        $response = $this->makeLoginRequest($user->email, static::TEST_PASSWORD);
        (new ResponseWriter('auth'))->name(__METHOD__)->handle($response);
        $response->assertStatus(200);

        $this->assertEquals($user->id, Auth::setToken($response->original['access_token'])->id());
    }

    public function testUserRegister()
    {
        $response = $this->makeRegisterRequest();
        (new ResponseWriter('auth'))->name(__METHOD__)->handle($response);
        $response->assertStatus(200);

        $responseToken = $response->original['access_token'];
        Auth::setToken($responseToken);
        $this->assertEquals(static::TEST_EMAIL, auth()->user()->email);
        $this->assertEquals(User::STATUS_USER, auth()->user()->status);
        Auth::logout();

        $loginResponse = $this->makeLoginRequest(static::TEST_EMAIL, static::TEST_PASSWORD);
        (new ResponseWriter('auth'))->name(__METHOD__)->handle($loginResponse);
        $loginResponse->assertStatus(200);

        $loginResponse = $this->makeLoginRequest(static::TEST_EMAIL, static::TEST_WRONG_PASSWORD);
        (new ResponseWriter('auth'))->name(__METHOD__)->handle($loginResponse);
        $loginResponse->assertStatus(422);
    }

    private function makeRegisterRequest()
    {
        return $this->json('POST', route('api.auth.register'), [
            'email' => static::TEST_EMAIL,
            'password' => static::TEST_PASSWORD,
            'password_confirmation' => static::TEST_PASSWORD,
            'first_name' => static::TEST_FIRST_NAME,
            'last_name' => static::TEST_LAST_NAME,
        ]);
    }

    private function makeLoginRequest($email, $password)
    {
        return $this->json('POST', route('api.auth.login'), [
            'email' => $email,
            'password' => $password
        ]);
    }
}
