<?php
namespace Sarus;

use Sarus\Client\Exception\HttpException;
use Sarus\Client\Exception\RuntimeException;

interface Client
{
    /**
     * @param string $method
     * @param string $uri
     * @param array|null $body
     *
     * @throws HttpException
     * @throws RuntimeException
     * @return \stdClass|array representing response body
     */
    public function request($method, $uri, array $body = null);

}