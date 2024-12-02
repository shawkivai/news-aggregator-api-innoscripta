<?php

namespace App\Enums\V1;

enum HttpStatus
{
    const SUCCESS_RESPONSE = 'success';

    const FAILED_RESPONSE = 'failed';

    const INVALID_RESPONSE = 'invalid';

    const UNAUTHORIZED_RESPONSE = 'unauthorized user';

    const SUCCESS = 200;

    const CREATED = 201;

    const BAD_REQUEST = 400;

    const UNAUTHORIZED = 401;

    const FAILED_REQUEST = 402;

    const FORBIDDEN_ERROR = 403;

    const NOT_FOUND = 404;

    const GONE_ERROR = 410;

    const VALIDATION_ERROR = 422;

    const WENT_WRONG = 444;

    const INELIGIBLE = 445;

    const NOT_ACCEPTABLE_HERE = 488;

    const INTERNAL_ERROR = 500;

    const NO_CONNECTION = 996;

    const INVALID_GIFT = 997;

    const NO_OTP = 999;

    const INVALID_OTP = 998;

    const RESPONSE_UNCHANGED = 304;

    const PRECONDITION_FAILED = 428;

    const REQEUST_ACCEPTED = 202;
}
