<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiException extends HttpException
{
    protected string $exceptionMessage = 'Internal server error';
    protected int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    public function __construct()
    {
        parent::__construct($this->statusCode, $this->exceptionMessage);
    }

    public function getExceptionMessage(): string
    {
        return $this->exceptionMessage;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
