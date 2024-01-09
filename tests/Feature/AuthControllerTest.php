<?php

use App\Http\Requests\SignUpPostRequest;
use App\Http\Requests\SpentCreateRequest;
use App\Models\User;
use App\Notifications\AccountConfirmNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\PersonalAccessToken;

use function Pest\Laravel\postJson;

uses()->group('feature')
    ->group('AuthController')
    ->group('AuthControllerFeature')
    ->in('AuthControllerFeature');


describe('AuthController@signUp feature', function (){

    beforeEach(function () {
        Notification::fake();

        $this->requestStub = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => fake()->password(8, 20)
        ];
    });

    it('returns 422 if data was not provided', function() {
        $response = postJson('/api/auth/signup', []);
        $response->assertStatus(422);
        $responseBody = $response->json();
        expect($responseBody['message'])->toBe('O nome deve ser informado. (and 2 more errors)');
        expect(sizeof($responseBody['errors']))->toBe(3);
    })->group('unit')->group('AuthController')->group('AuthControllerFeature')->group('AuthController@signUp');

    it('returns 422 if user with email already exists', function() {
        User::factory()->create([
            'name' => fake()->name(),
            'email' => 'test@test.com',
            'password' => Hash::make('any_pass'),
        ]);
        $response = postJson('/api/auth/signup', [
            ...$this->requestStub,
            'email' => 'test@test.com'
        ]);
        $response->assertStatus(422);
        $responseBody = $response->json();
        expect($responseBody['message'])->toBe('O email informado já existe');
        expect(sizeof($responseBody['errors']))->toBe(1);
    })->group('unit')->group('AuthController')->group('AuthControllerFeature')->group('AuthController@signUp');

    it('creates an user when succeds', function() {
        $response = postJson('/api/auth/signup', $this->requestStub);
        $response->assertStatus(200);
        $userCreated = User::first();

        Notification::assertSentTo(
            $userCreated,
            AccountConfirmNotification::class
        );

        expect($userCreated->name)->toBe($this->requestStub['name']);

    })->group('unit')->group('AuthController')->group('AuthControllerFeature')->group('AuthController@signUp');
});

describe('AuthController@signIn feature', function (){

    beforeEach(function () {
        $this->password = 'any_pass';
        $this->email = fake()->email();

        $this->userMock = User::factory()->create([
            'name' => fake()->name(),
            'email' => $this->email,
            'password' => Hash::make($this->password)
        ]);
    });

    it('returns 422 if data was not provided', function() {
        $response = postJson('/api/auth/signin', []);
        $response->assertStatus(422);
        $responseBody = $response->json();
        expect($responseBody['message'])->toBe('O email deve ser informado. (and 1 more error)');
        expect(sizeof($responseBody['errors']))->toBe(2);
    })->group('unit')->group('AuthController')->group('AuthControllerFeature')->group('AuthController@signIn');

    it('returns 401 if password provided is not equals registered', function() {
        $response = postJson('/api/auth/signin', [
            'email' => $this->email,
            'password' => 'incorrect'
        ]);
        $response->assertStatus(401);
        $responseBody = $response->json();
        expect($responseBody['error'])->toBe('Usuário ou senha inválidos!');
    })->group('unit')->group('AuthController')->group('AuthControllerFeature')->group('AuthController@signUp');

    it('returns 200 on success', function() {
        $response = postJson('/api/auth/signin', [
            'email' => $this->email,
            'password' => $this->password
        ]);
        $response->assertStatus(200);
        $tokenName = PersonalAccessToken::first()->name;
        $responseBody = $response->json();
        expect($tokenName)->toBe('api-token');
        expect($responseBody)->toHaveAttribute('token');
    })->group('unit')->group('AuthController')->group('AuthControllerFeature')->group('AuthController@signUp');
});
