<?php

use App\Http\Dto\Auth\UserSignUpDto;
use App\Models\User;
use App\Services\UserService;

uses()->group('unit')->group('UserService')->in('UserService');

beforeEach(function () {
    $this->signUpData = [
        'name' => fake()->name(),
        'email' => fake()->email(),
        'password' => fake()->password(8, 20)
    ];

    $this->userModelSpy = Mockery::spy(User::class);
    $this->userModelSpy->shouldReceive('create')->andReturn($this->userModelSpy);

    $this->userSignUpDtoStub = ((new UserSignUpDto)->toDto($this->signUpData));

    $this->sut = new UserService(
        $this->userModelSpy
    );
});

afterEach(function () {
    Mockery::close();
});

describe('UserService@create', function (){
    it('calls UserModel@create with correct values', function() {
        $this->sut->create($this->userSignUpDtoStub);
        $signUpData = $this->signUpData;
        $this->userModelSpy->shouldHaveReceived('create')->once()->with(Mockery::on(function ($data) use ($signUpData) {
            return $data['name'] == $signUpData['name']
                && $data['email'] == $signUpData['email']
                && $data['password'] == $signUpData['password'];
        }));
    })->group('unit')->group('UserService')->group('UserService@create');

    it('returns a valid user instance when succeds', function() {
        $response = $this->sut->create($this->userSignUpDtoStub);
        expect($response)->toBe($this->userModelSpy);
    })->group('unit')->group('UserService')->group('UserService@create');
});
