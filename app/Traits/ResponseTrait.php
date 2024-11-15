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

    public function apiFailedResponse(
        int $httpStatus,
        int $failedStatus,
        string $errorMsg,
    ): JsonResponse {
        $response = [];
        $response['status'] = HttpStatus::FAILED_RESPONSE;
        $response['error']['code'] = $failedStatus;
        $response['error']['description'] = $errorMsg;

        return response()->json($response, $httpStatus);

    }

    public function commonFailedResponse(
        $response_msg = null,
        int $httpStatus = HttpStatus::SUCCESS,
    ): JsonResponse {
        if (empty($response_msg)) {
            return $this->apiFailedResponse(
                HttpStatus::BAD_REQUEST,
                HttpStatus::FAILED_REQUEST,
                HttpStatus::FAILED_RESPONSE
            );
        }
        $response = [];
        $response['status'] = HttpStatus::FAILED_RESPONSE;
        $response['error']['code'] = $httpStatus;
        $response['error']['description'] = $response_msg;

        return response()->json($response, $httpStatus);
    }

    protected function handleResponse(ServiceResponseDTO $serviceResponseDTO): JsonResponse
    {

        if ($serviceResponseDTO->status == HttpStatus::FAILED_RESPONSE) {
            return $this->apiFailedResponse(
                $serviceResponseDTO->httpStatusCode,
                $serviceResponseDTO->apiHttpStatusCode,
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
