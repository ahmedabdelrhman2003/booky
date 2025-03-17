<?php

namespace App\Http\Controllers\Api\V1\User\Auth;

use App\DTOs\V1\User\Auth\VerifyOtpDTO;
use App\Enums\OTPActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ReSendOTPRequest;
use App\Http\Requests\Auth\VerifyOTPRequest;
use App\Http\Requests\V1\User\Auth\SendOTPRequest;
use App\Http\Resources\User\UserResource;
use App\Http\Responses\DataResponse;
use App\Http\Responses\ErrorResponse;
use App\Services\User\Auth\OtpService;
use App\Services\User\Auth\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class OtpController extends Controller
{
    public function __construct(private OtpService $otpService, private UserService $userService)
    {

    }

    public function reSend(ReSendOTPRequest $reSendOtpRequest): JsonResponse
    {
        try {
            $token = $this->otpService->reSendOtp($reSendOtpRequest['token']);
            return (new DataResponse(["verification_token" => $token]))->toJson();
        } catch (Exception $exception) {
            return (new ErrorResponse($exception->getMessage(), [], Response::HTTP_BAD_REQUEST))->toJson();
        } catch (Throwable $exception) {
            Log::error('error in resend otp function ', [$exception->getMessage()]);
            return (new ErrorResponse('oops something went wrong'))->toJson();
        }
    }

    public function verify(VerifyOTPRequest $verifyOtpRequest): JsonResponse
    {
        try {
            $dto = new VerifyOtpDTO($verifyOtpRequest->validated());

            [$result, $action] = $this->otpService->verifyOtp($dto);

            if ($action == OtpActions::RESET_PASSWORD->value) {
                return (new DataResponse(['password_token' => $result]))->toJson();
            }
            $token = $this->userService->generateToken($result);
            return (new DataResponse(new UserResource($result, $token)))->toJson();

        } catch (Exception $exception) {
            return (new ErrorResponse($exception->getMessage(), [], Response::HTTP_BAD_REQUEST))->toJson();
        } catch (Throwable $exception) {
            Log::error('error in verify function ', [$exception->getMessage()]);
            return (new ErrorResponse('oops something went wrong'))->toJson();
        }
    }
}
