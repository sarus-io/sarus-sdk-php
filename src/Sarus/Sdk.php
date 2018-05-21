<?php

namespace Sarus;

use Sarus\Client\Exception\HttpException;
use Sarus\Client\Exception\RuntimeException;

class Sdk
{
    /**
     * @var Client
     */
    private $client;

    /**
     * SDK constructor.
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @todo define user structure
     * @param array $uuids
     * @param $user
     * @param array $extraData
     *
     * @throws HttpException
     * @throws RuntimeException
     *
     * @return void
     */
    public function purchaseProduct(array $uuids, $user, array $extraData = [])
    {
        $body = [
            'product_uuids' => $uuids,
            'user' => $user
        ] + $extraData;

        $this->client->request('POST', '/v1/purchase', $body);
    }

    /**
     * @param string $uuid
     *
     * @throws HttpException
     * @throws RuntimeException
     *
     * @return void
     */
    public function unlinkProduct($uuid)
    {
        $this->client->request('POST', '/v1/products/unlink/' . $uuid);
    }

    /**
     * @param string $email
     *
     * @throws HttpException
     * @throws RuntimeException
     *
     * @return array
     */
    public function listEnrollments($email)
    {
        $body = ['email' => $email];
        $response = $this->client->request('GET', '/v1/participations', $body);
        return isset($response['data']) ? $response['data'] : [];
    }

    /**
     * @param string $email
     * @param array|string[] $productUuids
     *
     * @throws HttpException
     * @throws RuntimeException
     *
     * @return void
     */
    public function deactivateEnrollments($email, array $productUuids)
    {
        $body = [
            'email' => $email,
            'product_ids' => $productUuids,
        ];
        $this->client->request('PUT', '/v1/participation/deactivate', $body);
    }
}