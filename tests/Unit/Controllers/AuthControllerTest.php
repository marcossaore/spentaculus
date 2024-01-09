<?php

use App\Contracts\Services\User\CreateUserServiceContract;
use App\Http\Controllers\AuthController;
use App\Http\Dto\Auth\UserSignUpDto;
use App\Http\Requests\SignUpPostRequest;
use App\Notifications\AccountConfirmNotification;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Hashing\Hasher;

uses()->group('unit')->group('AuthController')->in('AuthController');

beforeEach(function () {
    $this->requestSignUpData = [
        'name' => fake()->name(),
        'email' => fake()->email(),
        'password' => fake()->password(8, 20)
    ];

    $this->requestSignUpSpy = Mockery::spy(SignUpPostRequest::class);
    $this->requestSignUpSpy->shouldReceive('validated')->andReturn($this->requestSignUpData);

    $this->hasherSpy = Mockery::spy(Hasher::class);
    $this->hasherSpy->shouldReceive('make')->andReturn('any_pass');

    $this->userSignUpDtoSpy = Mockery::spy(UserSignUpDto::class);
    $this->userSignUpDtoSpy->shouldReceive('toDto')->andReturn((new UserSignUpDto)->toDto([...$this->requestSignUpData, 'password' => 'any_pass']));

    $this->accountConfirmNotificationSpy = Mockery::spy(AccountConfirmNotification::class);

    $this->userServiceSpy = Mockery::spy(CreateUserServiceContract::class);
    $this->userServiceSpy->shouldReceive('notify');
    $this->userServiceSpy->shouldReceive('create')->andReturn($this->userServiceSpy);

    $this->authManagerSpy = Mockery::spy(AuthManager::class);

    $this->sut = new AuthController(
        $this->hasherSpy,
        $this->userSignUpDtoSpy,
        $this->userServiceSpy,
        $this->accountConfirmNotificationSpy,
        $this->authManagerSpy
    );
});

afterEach(function () {
    Mockery::close();
});

describe('AuthController@signUp', function (){

    it('calls SignUpPostRequest@requestedValidated', function() {
        $this->sut->signUp($this->requestSignUpSpy);
        $this->requestSignUpSpy->shouldHaveReceived('validated')->once();
    })->group('unit')->group('AuthController')->group('AuthController@signUp');

    it('calls Hasher@make with correct password', function() {
        $this->sut->signUp($this->requestSignUpSpy);
        $this->hasherSpy->shouldHaveReceived('make')->once()->with($this->requestSignUpData['password']);
    })->group('unit')->group('AuthController')->group('AuthController@signUp');

    it('calls UserSignUpDto@toDto with correct values', function() {
        $this->sut->signUp($this->requestSignUpSpy);
        $requestData = $this->requestSignUpData;
        $this->userSignUpDtoSpy->shouldHaveReceived('toDto')->once()->with(Mockery::on(function ($data) use ($requestData) {
            return $data['name'] == $requestData['name']
              && $data['email'] == $requestData['email']
              && $data['password'] == 'any_pass';
        }));
    })->group('unit')->group('AuthController')->group('AuthController@signUp');

    it('calls UserService@create with UserSignUpDto instance', function() {
        $this->sut->signUp($this->requestSignUpSpy);
        $requestData = $this->requestSignUpData;
        $this->userServiceSpy->shouldHaveReceived('create')->once()->with(Mockery::on(function ($data) use ($requestData) {
            return $data->name == $requestData['name']
                && $data->email == $requestData['email']
                && $data->password == 'any_pass';
        }));
    })->group('unit')->group('AuthController')->group('AuthController@signUp');

    it('calls UserService@notify with AccountConfirmNotification instance', function() {
        $this->sut->signUp($this->requestSignUpSpy);
        $this->userServiceSpy->shouldHaveReceived('notify');
    })->group('unit')->group('AuthController')->group('AuthController@signUp');

    it('no returns on success', function() {
        $response = $this->sut->signUp($this->requestSignUpSpy);
        expect($response)->toBe(null);
    })->group('unit')->group('AuthController')->group('AuthController@signUp');
});
