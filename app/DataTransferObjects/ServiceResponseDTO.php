<?php

namespace App\DataTransferObjects;

class ServiceResponseDTO extends AbstractDTO
{
    /**
     * Status could be either "success" or "failed"
     */
    public string $status;

    /**
     * HTTP Status Code
     */
    public int $httpStatusCode;

    /**
     * Response Payload
     *
     * It could be Total or Partial Payload
     *
     * @var mixed|null
     */
    public mixed $response;

    /**
     * Response Message
     */
    public ?string $responseMessage;

    /**
     * HTTP Response headers
     */
    public array $headers;
}
