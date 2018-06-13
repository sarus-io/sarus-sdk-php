<?php

namespace Sarus\Request;

use Sarus\Request;

class Ping implements Request
{
    use Request\SerializableTrait;

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return '/v1/ping';
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return 'GET';
    }
}
