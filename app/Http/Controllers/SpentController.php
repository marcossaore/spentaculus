<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Controllers\Traits\Paginatable;
use App\Http\Dto\Spent\SpentCreateDto;
use App\Http\Dto\Spent\SpentUpdateDto;
use App\Http\Errors\NotFoundError;
use App\Http\Requests\SpentCreateRequest;
use App\Http\Errors\ServerError;
use App\Http\Requests\SpentUpdateRequest;
use App\Http\Resources\SpentResource;
use App\Notifications\SpentCreatedNotification;
use App\Notifications\SpentUpdatedNotification;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;

class SpentController extends Controller
{

    use Paginatable;

    public function __construct(
        private readonly SpentCreateDto $spentCreateDto,
        private readonly SpentUpdateDto $spentUpdateDto,
        private readonly AuthManager $authManager
    )
    {}

    public function store(SpentCreateRequest $request){
        try {
            $data = $request->validated();
            $spentCreateDto = $this->spentCreateDto->toDto($data);
            $user = $this->authManager->user();
            $spent = $user->spents()->save($spentCreateDto->toEntity())->refresh();
            $user->notify(new SpentCreatedNotification($spent));
            return new SpentResource($spent);
        } catch (Exception $exception) {
            $serverError = new ServerError();
            return response()->json(['error' => $serverError->getMessage(), $serverError->getStatusCode()]);
        }
    }

    public function update($id, SpentUpdateRequest $request){
        try {
            $user = $this->authManager->user();
            $spent = $user->spents()->find($id);
            if (!$spent) {
                throw new NotFoundError('Despesa não encontrada!');
            }
            $data = $request->validated();
            $spentUpdateDto = $this->spentUpdateDto->toDto($data);
            $spent->update($spentUpdateDto->toArray());
            $user->notify(new SpentUpdatedNotification($spent));
            return new SpentResource($spent);
        } catch (NotFoundError $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getStatusCode());
        } catch (Exception $exception) {
            $serverError = new ServerError();
            return response()->json(['error' => $serverError->getMessage(), $serverError->getStatusCode()]);
        }
    }

    public function index(Request $request){
        try {
            $pagParams = $this->applyPaginate($request);
            $user = $this->authManager->user();
            $spents = $user->spents()->paginate(
                $pagParams->limit,
                ['*'],
                'page',
                $pagParams->page
            );
            return SpentResource::collection($spents);
        } catch (Exception $exception) {
            $serverError = new ServerError();
            return response()->json(['error' => $serverError->getMessage(), $serverError->getStatusCode()]);
        }
    }

    public function show($id)
    {
        try {
            $user = $this->authManager->user();
            $spent = $user->spents()->find($id);
            if (!$spent) {
                throw new NotFoundError('Despesa não encontrada!');
            }
            return new SpentResource($spent);
        } catch (NotFoundError $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getStatusCode());
        } catch (Exception $exception) {
            $serverError = new ServerError();
            return response()->json(['error' => $serverError->getMessage(), $serverError->getStatusCode()]);
        }
    }

    public function destroy($id)
    {
        try {
            $user = $this->authManager->user();
            $spent = $user->spents()->find($id);
            if (!$spent) {
                throw new NotFoundError('Despesa não encontrada!');
            }
            $spent->delete();
            return response()->json([], 204);
        } catch (NotFoundError $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getStatusCode());
        }
        catch (Exception $exception) {
            $serverError = new ServerError();
            return response()->json(['error' => $serverError->getMessage(), $serverError->getStatusCode()]);
        }
    }

}
