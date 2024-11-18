<?php

namespace App\Http\Controllers\V1;

use App\Domain\V1\User\Services\UserPreferenceService;
use App\Enums\V1\HttpStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserPreferenceRequest;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class UserPreferenceController extends Controller
{
    use ResponseTrait;

    public function __construct(
        protected UserPreferenceService $userPreferenceService
    ) {}

    public function __invoke(UserPreferenceRequest $request)
    {
        try {
            return $this->handleResponse($this->userPreferenceService->update($request->validated()));
        } catch (\Throwable $th) {
            return $this->apiFailedResponse(HttpStatus::INTERNAL_ERROR, $th->getMessage());
        }
    }
}
