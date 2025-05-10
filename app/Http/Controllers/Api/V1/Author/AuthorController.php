<?php

namespace App\Http\Controllers\Api\V1\Author;

use App\DTOs\V1\User\Author\ListAuthorsDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Author\AllAuthorsRequest;
use App\Http\Resources\Author\AuthorCollection;
use App\Http\Responses\DataResponse;
use App\Http\Responses\ErrorResponse;
use App\Services\Author\AuthorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;


class AuthorController extends Controller
{
    public function __construct(private readonly AuthorService $authorService)
    {
    }

    public function index(AllAuthorsRequest $request): JsonResponse
    {
        try {
            $dto = new ListAuthorsDTO($request->validated());
            $authors = $this->authorService->all($dto);
            $collection = new AuthorCollection($authors);
            return (new DataResponse($collection))->toJson();
        } catch (Throwable $exception) {
            Log::error('error in index author function ', [$exception->getMessage()]);
            return (new ErrorResponse('Oops something went wrong -_- !'))->toJson();
        }
    }
}
