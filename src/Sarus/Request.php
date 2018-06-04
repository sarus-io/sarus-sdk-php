<?php

namespace Sarus;

interface Request extends \Serializable
{
    /**
     * @return array|null
     */
    public function getBody();

    /**
     * @return string
     */
    public function getPath();

    /**
     * @return string
     */
    public function getMethod();
}
