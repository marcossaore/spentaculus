<?php

namespace App\Http\Controllers;

use Exception;
use App\Contracts\Services\User\CreateUserServiceContract;
use App\Http\Dto\Auth\UserSignUpDto;
use App\Http\Errors\ServerError;
use App\Http\Errors\UnauthorizedError;
use App\Http\Requests\SignInPostRequest;
use App\Http\Requests\SignUpPostRequest;
use App\Notifications\AccountConfirmNotification;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Hashing\Hasher;

class AuthController extends Controller
{

    public function __construct(
        private readonly Hasher $hasher,
        private readonly UserSignUpDto $UserSignUpDto,
        private readonly CreateUserServiceContract $userService,
        private readonly AccountConfirmNotification $accountConfirmNotification,
        private readonly AuthManager $authManager,
    )
    {}

    public function signUp(SignUpPostRequest $request){
        try {
            $data = $request->validated();
            $hashPassword = $this->hasher->make($data['password']);
            $userSignUpDto = $this->UserSignUpDto->toDto([...$data, 'password' => $hashPassword]);
            $user = $this->userService->create($userSignUpDto);
            $user->notify($this->accountConfirmNotification);
        } catch (\Exception $exception) {
            $serverError = new ServerError();
            return response()->json(['error' => $serverError->getMessage(), $serverError->getStatusCode()]);
        }
    }

    public function signIn(SignInPostRequest $request){
        try {
            $credentials = $request->validated();
            if(!$this->authManager->attempt($credentials)) {
                throw new UnauthorizedError();
            }
            $user = $this->authManager->user();
            $token = $user->createToken('api-token')->plainTextToken;
            return response()->json(['token' => $token]);
        } catch (UnauthorizedError $exception) {
             return response()->json(['error' => $exception->getMessage()], $exception->getStatusCode());
        }
        catch (Exception $exception) {
            $serverError = new ServerError();
            return response()->json(['error' => $serverError->getMessage(), $serverError->getStatusCode()]);
        }
    }
}
