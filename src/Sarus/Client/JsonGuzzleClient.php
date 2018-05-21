<?php
namespace Sarus\Client;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Sarus\Client;
use Sarus\Client\Exception\HttpException;
use Sarus\Client\Exception\RuntimeException;

class JsonGuzzleClient implements Client
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $guzzle;

    public function __construct(\GuzzleHttp\Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * {@inheritdoc}
     */
    public function request($method, $uri, array $body = null)
    {
        $jsonBody = null;
        if (null !== $body) {
            $jsonBody = $this->encodeJson($body);
        }

        try {
            $response = $this->guzzle->request(
                $method,
                $uri,
                ['body' => $jsonBody]
            );
        } catch (RequestException $e) {
            throw HttpException::create($e->getMessage(), $e->getRequest(), $e->getResponse());
        } catch (GuzzleException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        $body = $response->getBody()->getContents();

        return $this->decodeJson($body);
    }

    /**
     * @param string $body
     * @return \stdClass
     */
    private function decodeJson($body)
    {
        try {
            return \GuzzleHttp\json_decode($body, true);
        } catch (\Exception $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param array $data
     * @return string
     */
    private function encodeJson(array $data)
    {
        try {
            return \GuzzleHttp\json_encode($data);
        } catch (\Exception $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }
}