<?php

namespace App\Http\Controllers\Api\V1\Payment;

use App\DTOs\V1\Payment\PaymobWebhookDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\CreateIntentRequest;
use App\Http\Responses\DataResponse;
use App\Http\Responses\ErrorResponse;
use App\Services\Book\BookService;
use App\Services\Payment\PaymentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;


class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentServiceInterface $paymentService,
        private readonly BookService             $bookService
    )
    {
    }

    public function createIntention(CreateIntentRequest $request): JsonResponse
    {
        try {
            $book = $this->bookService->findById($request->book_id);
            $clientSecret = $this->paymentService->createIntention($book);
            return (new DataResponse(['client_secret' => $clientSecret]))->toJson();
        } catch (Throwable $exception) {
            Log::error('error in intent function ', [$exception->getMessage()]);
            return (new ErrorResponse('Oops something went wrong -_- !'))->toJson();
        }
    }

    public function handleWebhook(Request $request): void
    {
        try {
            $dto = new PaymobWebhookDTO($request->all());
             $this->paymentService->handleWebhook($dto);
        } catch (Throwable $exception) {
            Log::error('error in webhook function ', [$exception->getMessage()]);
        }
    }


}
