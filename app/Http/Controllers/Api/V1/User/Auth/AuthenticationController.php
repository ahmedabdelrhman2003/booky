<?php

namespace App\Http\Controllers\Api\V1\User\Auth;

use App\DTOs\V1\User\Auth\LoginSocialDTO;
use App\DTOs\V1\User\Auth\LoginUserDTO;
use App\DTOs\V1\User\Auth\RegisterUserDTO;
use App\DTOs\V1\User\Auth\ReSendOtpDTO;
use App\DTOs\V1\User\Auth\ResetPasswordDTO;
use App\DTOs\V1\User\Auth\UpdateProfileDTO;
use App\Enums\OTPActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\RequestResetPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\SocialLoginRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Resources\Book\BookCollection;
use App\Http\Resources\User\UserResource;
use App\Http\Responses\DataResponse;
use App\Http\Responses\ErrorResponse;
use App\Services\User\Auth\OtpService;
use App\Services\User\Auth\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

//use App\Http\Resources\UserResource;

class AuthenticationController extends Controller
{
    public function __construct(private UserService $userService, private OtpService $otpService)
    {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $dto = new RegisterUserDTO($request->validated());
            $user = $this->userService->register($dto);
            $data = [
                'action' => OTPActions::VERIFY_EMAIL->value,
                'user_id' => $user->id,
                'email' => $user->email
            ];
            $dto = new ReSendOtpDTO($data);
            $token = $this->otpService->sendOtp($dto);
            DB::commit();

            return (new DataResponse(['verification_token' => $token]))->toJson();
        } catch (Throwable $exception) {
            Log::error('error in register function ', [$exception->getMessage()]);
            return (new ErrorResponse('Oops something went wrong -_- !'))->toJson();
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $dto = new LoginUserDTO($request->validated());
            $user = $this->userService->login($dto);
            if (!$user->account_verified) {
                $data = [
                    'action' => OTPActions::VERIFY_EMAIL->value,
                    'user_id' => $user->id,
                    'email' => $user->email
                ];
                $dto = new ReSendOtpDTO($data);
                $token = $this->otpService->sendOtp($dto);
                return (new DataResponse(['verification_token' => $token]))->toJson();
            }
            $token = $this->userService->generateToken($user);
            $resource = new UserResource($user, $token);

            return (new DataResponse($resource))->toJson();
        } catch (Exception $exception) {
            Log::error('error in login function ', [$exception->getMessage()]);
            return (new ErrorResponse($exception->getMessage(), [], Response::HTTP_BAD_REQUEST))->toJson();
        } catch (Throwable $exception) {
            Log::error('error in login function ', [$exception->getMessage()]);
            return (new ErrorResponse('something_went_wrong'))->toJson();
        }
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $dto = new ResetPasswordDTO($request->validated());
             $this->userService->resetPassword($dto);
            return (new DataResponse(null,'password has been reset successfully'))->toJson();
        } catch (Exception $exception) {
            return (new ErrorResponse($exception->getMessage(), [], Response::HTTP_BAD_REQUEST))->toJson();
        } catch (Throwable $exception) {
            Log::error('error in resetPassword function ', [$exception->getMessage()]);
            return (new ErrorResponse('something_went_wrong'))->toJson();
        }
    }

    public function logout(): JsonResponse
    {
        try {
            $this->userService->logout();
            return (new DataResponse(null, 'logged out Successfully'))->toJson();
        } catch (Throwable $exception) {
            Log::error('error in logout function ', [$exception->getMessage()]);
            return (new ErrorResponse('something_went_wrong'))->toJson();
        }
    }

    public function socialLogin(SocialLoginRequest $request): JsonResponse
    {
        try {
            $dto = new LoginSocialDTO($request->validated());
            $user = $this->userService->socialLogin($dto);
            $token = $this->userService->generateToken($user);
            $resource = new UserResource($user, $token);

            return (new DataResponse($resource))->toJson();
        } catch (Exception $exception) {
            return (new ErrorResponse($exception->getMessage(), [], Response::HTTP_BAD_REQUEST))->toJson();
        } catch (Throwable $exception) {
            Log::error('error in social login function ', [$exception->getMessage()]);
            return (new ErrorResponse(__('something_went_wrong')))->toJson();
        }
    }

    public function requestResetPassword(RequestResetPasswordRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->getByEmail($request['email']);
            $data = [
                'user_id' => $user->id,
                'action' => OTPActions::RESET_PASSWORD->value,
                'email' => $user->email
            ];
            $dto = new ReSendOtpDTO($data);

            $token = $this->otpService->sendOtp($dto);

            return (new DataResponse(['verification_token' => $token]))->toJson();
        } catch (Throwable $exception) {
            Log::error('error in request reset password function ', [$exception->getMessage()]);
            return (new ErrorResponse('Oops something went wrong -_- !'))->toJson();
        }

    }

    public function favBooks(): JsonResponse
    {
        try {
           $books =  $this->userService->getFavBooks();
           $collection = new BookCollection($books);
            return (new DataResponse($collection))->toJson();
        } catch (Throwable $exception) {
            Log::error('error in favBooks function ', [$exception->getMessage()]);
            return (new ErrorResponse('something_went_wrong'))->toJson();
        }
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $dto = new UpdateProfileDTO($request->validated());
            $user =  $this->userService->updateProfile($dto);
            $resource = new UserResource($user);
            return (new DataResponse($resource))->toJson();
        } catch (Throwable $exception) {
            Log::error('error in updateProfile function ', [$exception->getMessage()]);
            return (new ErrorResponse('something_went_wrong'))->toJson();
        }
    }

}
