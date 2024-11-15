<?php

namespace App\Enums\V1;

enum HttpStatus
{
    public const SUCCESS_RESPONSE = 'success';

    public const FAILED_RESPONSE = 'failed';

    public const INVALID_RESPONSE = 'invalid';

    public const UNAUTHORIZED_RESPONSE = 'unauthorized user';

    public const SUCCESS = 200;

    public const CREATED = 201;

    public const BAD_REQUEST = 400;

    public const UNAUTHORIZED = 401;

    public const FAILED_REQUEST = 402;

    public const FORBIDDEN_ERROR = 403;

    public const NOT_FOUND = 404;

    public const GONE_ERROR = 410;

    public const VALIDATION_ERROR = 422;

    public const WENT_WRONG = 444;

    public const INELIGIBLE = 445;

    public const NOT_ACCEPTABLE_HERE = 488;

    public const INTERNAL_ERROR = 500;

    public const NO_CONNECTION = 996;

    public const INVALID_GIFT = 997;

    public const NO_OTP = 999;

    public const INVALID_OTP = 998;

    public const RESPONSE_UNCHANGED = 304;

    public const PRECONDITION_FAILED = 428;

    public const REQEUST_ACCEPTED = 202;
}
