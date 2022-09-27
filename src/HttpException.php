<?php

namespace nickdnk\ZeroBounce;

use Psr\Http\Message\ResponseInterface;

class HttpException extends APIError
{

    private $response;

    public function __construct(string $message, ResponseInterface $response)
    {
        parent::__construct($message);
        $this->response = $response;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

}
