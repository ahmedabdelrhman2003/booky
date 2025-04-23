<?php

namespace App\Http\Controllers\Api\V1\ContactUs;

use App\DTOs\V1\User\General\StoreContactUsDTO;
use App\Enums\BookLangEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\General\StoreContactUsRequest;
use App\Http\Resources\Book\CategoryResource;
use App\Http\Responses\DataResponse;
use App\Http\Responses\ErrorResponse;
use App\Services\Book\CategoryService;
use App\Services\ContactUs\ContactUsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;


class ContactUsController extends Controller
{

    public function __construct(private readonly ContactUsService $service)
    {
    }

    public function store(StoreContactUsRequest $request): JsonResponse
    {
        try {
            $dto = new StoreContactUsDTO($request->validated());
            $this->service->store($dto);

            return (new DataResponse(null,'Your inquiry stored successfully'))->toJson();
        } catch (Throwable $exception) {
            Log::error('error in store function in contactUsController ', [$exception->getMessage()]);
            return (new ErrorResponse('Oops something went wrong -_- !'))->toJson();
        }
    }

}
