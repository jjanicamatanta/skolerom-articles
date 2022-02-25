<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class NotFoundApiException extends ApiException
{
    protected string $exceptionMessage = 'Not found';
    protected int $statusCode = Response::HTTP_NOT_FOUND;
}
