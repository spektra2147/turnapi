<?php

namespace App\Http\Controllers;

use App\Http\Requests\CallbackRequest;
use App\Services\ActivityService;
use App\Services\CallbackService;
use App\Services\HashService;
use Justfeel\Response\ResponseCodes;
use Justfeel\Response\ResponseResult;

class CallbackController extends Controller
{
    private $hashService;
    private $activityService;
    private $callbackService;

    public function __construct(
        HashService     $hashService,
        ActivityService $activityService,
        CallbackService $callbackService
    )
    {
        $this->hashService = $hashService;
        $this->activityService = $activityService;
        $this->callbackService = $callbackService;
    }

    public function processCallback(CallbackRequest $request)
    {
        try {
            $isValid = $this->hashService->validateHash($request);

            if ($isValid) {

                $canEnter = $this->activityService->canUserEnter($request->user_id);

                if (!$canEnter) {
                    $this->callbackService->sendCallbackRequest($request, false);
                    return ResponseResult::generate(false, 'Kullanıcı günlük geçiş limitine ulaştı', ResponseCodes::HTTP_BAD_REQUEST);
                }

                $canEnterCafeteria = $this->activityService->canEnterCafeteria();

                if (!$canEnterCafeteria) {
                    $this->callbackService->sendCallbackRequest($request, false);
                    return ResponseResult::generate(false, 'Günlük yemekhane geçiş limitine ulaşıldı', ResponseCodes::HTTP_BAD_REQUEST);
                }

                if ($this->activityService->findActivity($request->order_id)) {
                    $this->callbackService->sendCallbackRequest($request, false);
                    return ResponseResult::generate(false, 'Bu sipariş id işlendi!', ResponseCodes::HTTP_BAD_REQUEST);
                }

                $this->activityService->saveActivity($request->user_id, $request->order_id);

                $this->callbackService->sendCallbackRequest($request);

                return ResponseResult::generate(true, 'Callback başarıyla işlendi ve kayıt yapıldı', ResponseCodes::HTTP_OK);
            } else {
                return ResponseResult::generate(false, 'Hash doğrulama başarısız', ResponseCodes::HTTP_FORBIDDEN);
            }
        } catch (\Exception $exception) {
            return ResponseResult::generate(false, $exception->getMessage(), ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
