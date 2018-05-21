<?php

namespace Sarus\Client\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpException extends \RuntimeException
{
    private $request;
    private $response;

    public static function create($message, RequestInterface $request, ResponseInterface $response = null)
    {
        $self = new static((string) $message);
        $self->request = $request;
        $self->response = $response;
        return $self;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return ResponseInterface|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return bool
     */
    public function hasResponse()
    {
        return $this->response instanceof ResponseInterface;
    }
}