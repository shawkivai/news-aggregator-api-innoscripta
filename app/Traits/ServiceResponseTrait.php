<?php

namespace App\Traits;

use App\DataTransferObjects\ServiceResponseDTO;
use App\Enums\V1\HttpStatus;

trait ServiceResponseTrait
{
    private $statusCode;

    private $errorMessage;

    private $httpCode = HttpStatus::BAD_REQUEST;

    public function throwCustomizedError(
        $statusCode,
        string $errorMessage,
        $httpCode = HttpStatus::BAD_REQUEST
    ) {
        $this->statusCode = $statusCode;
        $this->errorMessage = $errorMessage;
        $this->httpCode = $httpCode;

        throw new \Exception($this->errorMessage);
    }

    protected function respondSuccess($response, int $httpCode = HttpStatus::SUCCESS, ?string $messge = null, array $headers = [])
    {
        return new ServiceResponseDTO([
            'status' => HttpStatus::SUCCESS_RESPONSE,
            'response' => $response,
            'responseMessage' => $messge ? ucfirst(strtolower($messge)) : HttpStatus::SUCCESS_RESPONSE,
            'httpStatusCode' => $httpCode,
            'headers' => $headers,
        ]);
    }

    /**
     * @return ServiceResponseDTO
     *
     * @deprecated Avoid this use case, instead use FAILED_RESPONSE_V2
     */
    protected function respondInvalid($response, int $httpCode, array $headers = [])
    {
        return new ServiceResponseDTO([
            'status' => HttpStatus::INVALID_RESPONSE,
            'response' => $response,
            'responseMessage' => HttpStatus::INVALID_RESPONSE,
            'httpStatusCode' => $httpCode,
            'headers' => $headers,
        ]);
    }

    protected function respondFailed(string $responseMessage, int $httpCode, array $headers = [])
    {
        return new ServiceResponseDTO([
            'status' => HttpStatus::FAILED_RESPONSE,
            'response' => [],
            'responseMessage' => ucfirst(strtolower($responseMessage)),
            'httpStatusCode' => $httpCode,
            'headers' => $headers,
        ]);
    }

    /**
     * Handle if the required values not set before calling
     * the ServiceResponseDTO instance.
     *
     * @param  string  $action
     * @param  \Exception  $exception
     * @return void
     */
    protected function handleException($action, $exception)
    {
        if (empty($this->errorMessage)) {
            $this->errorMessage = HttpStatus::FAILED_RESPONSE;
        }

        if (empty($this->statusCode)) {
            $this->statusCode = HttpStatus::FAILED_REQUEST;
        }

        if (empty($this->httpCode)) {
            $this->httpCode = HttpStatus::BAD_REQUEST;
        }
    }
}
