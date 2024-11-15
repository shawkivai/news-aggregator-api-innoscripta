<?php

namespace App\DataTransferObjects;

class ServiceResponseDTO extends AbstractDTO
{
    /**
     * Status could be either "success" or "failed"
     *
     * @var string
     */
    public $status;

    /**
     * HTTP Status Code
     *
     * @var int
     */
    public $httpStatusCode;

    /**
     * Response Payload
     *
     * It could be Total or Partial Payload
     *
     * @var mixed|null
     */
    public $response;

    /**
     * Response Message
     */
    public ?string $responseMessage;

    /**
     * HTTP Response headers
     */
    public array $headers;
}
