<?php

namespace Unicodeveloper\Paystack\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

class PaystackRequestException extends Exception
{
    protected ?Response $response;

    public function __construct(string $message, ?Response $response = null)
    {
        parent::__construct($message);
        $this->response = $response;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }
}
