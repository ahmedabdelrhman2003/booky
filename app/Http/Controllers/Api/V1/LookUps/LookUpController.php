<?php

namespace App\Http\Controllers\Api\V1\LookUps;

use App\Enums\BookLangEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Book\CategoryResource;
use App\Http\Resources\General\FaqResource;
use App\Http\Responses\DataResponse;
use App\Http\Responses\ErrorResponse;
use App\Services\Book\CategoryService;
use App\Services\General\FaqService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;


class LookUpController extends Controller
{

    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly FaqService      $faqService
    )
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
            $faqs = $this->faqService->all();
            $collection = FaqResource::collection($faqs);
            return (new DataResponse(['faqs'=>$collection]))->toJson();
        } catch (Throwable $exception) {
            Log::error('error in faqs lookups function ', [$exception->getMessage()]);
            return (new ErrorResponse('Oops something went wrong -_- !'))->toJson();
        }
    }

}
