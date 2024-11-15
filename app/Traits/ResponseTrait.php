<?php

namespace App\Traits;

use App\DataTransferObjects\ServiceResponseDTO;
use App\Enums\V1\HttpStatus;
use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    /**
     * Function for Success response
     */
    public function apiSuccessResponse(int $httpStatus, array|object|null $response, string $responseMessage): JsonResponse
    {
        return response()->json([
            'status' => HttpStatus::SUCCESS_RESPONSE,
            'message' => $responseMessage,
            'data' => $response,
        ], $httpStatus);
    }

    /**
     * Function for Failure response with simplified parameters
     */
    public function apiFailedResponse(int $httpStatus, string $errorMessage): JsonResponse
    {
        return response()->json([
            'status' => HttpStatus::FAILED_RESPONSE,
            'error' => [
                'code' => $httpStatus,
                'description' => $errorMessage,
            ],
        ], $httpStatus);
    }

    /**
     * Common Failure response
     */
    public function commonFailedResponse(
        ?string $responseMessage = null,
        int $httpStatus = HttpStatus::BAD_REQUEST
    ): JsonResponse {
        if (empty($responseMessage)) {
            return $this->apiFailedResponse(
                HttpStatus::BAD_REQUEST,
                HttpStatus::FAILED_RESPONSE
            );
        }

        return $this->apiFailedResponse($httpStatus, $responseMessage);
    }

    /**
     * Handle response based on ServiceResponseDTO status
     */
    protected function handleResponse(ServiceResponseDTO $serviceResponseDTO): JsonResponse
    {
        if ($serviceResponseDTO->status == HttpStatus::FAILED_RESPONSE) {
            return $this->apiFailedResponse(
                $serviceResponseDTO->httpStatusCode,
                $serviceResponseDTO->responseMessage
            );
        } elseif ($serviceResponseDTO->status == HttpStatus::SUCCESS_RESPONSE) {
            return $this->apiSuccessResponse(
                $serviceResponseDTO->httpStatusCode,
                $serviceResponseDTO->response,
                $serviceResponseDTO->responseMessage
            );
        } elseif ($serviceResponseDTO->status == HttpStatus::INVALID_RESPONSE) {
            return $this->commonFailedResponse(
                $serviceResponseDTO->response,
                $serviceResponseDTO->httpStatusCode
            );
        }

        return $this->commonFailedResponse();
    }
}
