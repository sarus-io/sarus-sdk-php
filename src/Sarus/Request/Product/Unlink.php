<?php

namespace Sarus\Request\Product;

use Sarus\Request;

class Unlink implements Request
{
    use Request\SerializableTrait;

    /**
     * @var string
     */
    private $productUuid;

    public function __construct($productUuid)
    {
        $this->productUuid = $productUuid;
    }

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
        return '/v1/products/unlink/' . $this->productUuid;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return 'POST';
    }
}
