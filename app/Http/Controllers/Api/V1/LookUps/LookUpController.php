<?php

namespace App\Http\Controllers\Api\V1\LookUps;

use App\DTOs\V1\User\Book\SetUpDTO;
use App\Enums\BookLangEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Book\AllBooksRequest;
use App\Http\Requests\Book\GetBookRequest;
use App\Http\Resources\Book\BookCollection;
use App\Http\Resources\Book\BookResource;
use App\Http\Resources\Book\CategoryResource;
use App\Http\Responses\DataResponse;
use App\Http\Responses\ErrorResponse;
use App\Services\Book\BookService;
use App\Services\Book\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;


class LookUpController extends Controller
{

    public function __construct(private readonly CategoryService $categoryService)
    {
    }

    public function index(): JsonResponse
    {
        try {
            $language = BookLangEnum::values();
            $categories=$this->categoryService->all();
            $resource =  CategoryResource::collection($categories);

            return (new DataResponse(['language'=>$language,'categories'=>$resource]))->toJson();
        } catch (Throwable $exception) {
            Log::error('error in index lookups function ', [$exception->getMessage()]);
            return (new ErrorResponse('Oops something went wrong -_- !'))->toJson();
        }
    }

    public function faqs(): JsonResponse
    {
        try {

            return (new DataResponse())->toJson();
        } catch (Throwable $exception) {
            Log::error('error in index lookups function ', [$exception->getMessage()]);
            return (new ErrorResponse('Oops something went wrong -_- !'))->toJson();
        }
    }

}
