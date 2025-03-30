<?php

namespace App\Http\Controllers\Api\V1\Book;

use App\DTOs\V1\User\Book\SetUpDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Book\AllBooksRequest;
use App\Http\Requests\Book\GetBookRequest;
use App\Http\Resources\Book\BookCollection;
use App\Http\Resources\Book\BookResource;
use App\Http\Responses\DataResponse;
use App\Http\Responses\ErrorResponse;
use App\Services\Book\BookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;


class BookController extends Controller
{
    public function __construct(private readonly BookService $bookService)
    {
    }

    public function index(AllBooksRequest $request): JsonResponse
    {
        try {
            $dto = new SetUpDTO($request->validated());
            $books = $this->bookService->all($dto);
            $collection = new BookCollection($books);
            return (new DataResponse($collection))->toJson();
        } catch (Throwable $exception) {
            Log::error('error in index books function ', [$exception->getMessage()]);
            return (new ErrorResponse('Oops something went wrong -_- !'))->toJson();
        }
    }

    public function show(GetBookRequest $request, int $id): JsonResponse
    {
        try {
            $books = $this->bookService->findById($id);
            $resource = new BookResource($books);
            return (new DataResponse($resource))->toJson();
        } catch (Throwable $exception) {
            Log::error('error in show books function ', [$exception->getMessage()]);
            return (new ErrorResponse('Oops something went wrong -_- !'))->toJson();
        }
    }


}
