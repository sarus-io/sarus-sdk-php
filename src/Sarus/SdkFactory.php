<?php
/**
 * Created by PhpStorm.
 * User: makedo3
 * Date: 18.05.18
 * Time: 16:44
 */

namespace Sarus;


use Sarus\Client\JsonGuzzleClient;

class SdkFactory
{
    const DEFAULT_BASE_URI = 'https://api.sarus.io';
    const DEFAULT_TIMEOUT  = 30;

    /**
     * @var string
     */
    private $secret;
    /**
     * @var string
     */
    private $baseUri;
    /**
     * @var int
     */
    private $timeout;
    /**
     * @var bool
     */
    private $sslVerify;


    /**
     * @param $secret
     * @param string $baseUri
     * @param int $timeout
     * @param bool $sslVerify
     */
    public function __construct(
        $secret,
        $baseUri = self::DEFAULT_BASE_URI,
        $timeout = self::DEFAULT_TIMEOUT,
        $sslVerify = true
    ) {
        $this->secret    = $this->filterValidateSecret($secret);
        $this->baseUri   = $this->filterValidateBaseUri($baseUri);
        $this->timeout   = $this->filterValidateTimeout($timeout);
        $this->sslVerify = $this->filterValidateSslVerify($sslVerify);
    }

    /**
     * @return Sdk
     */
    public function create()
    {
        return new Sdk(
            new JsonGuzzleClient(
                new \GuzzleHttp\Client([
                    'base_uri'        => $this->baseUri,
                    'timeout'         => $this->timeout,
                    'verify'          => $this->sslVerify,
                    'allow_redirects' => false,
                    'cookies'         => false,
                    'headers' => [
                        'Content-Type'  => 'application/json',
                        'Accept'        => 'application/json',
                        'Authorization' => 'Bearer ' . $this->secret
                    ]
                ])
            )
        );
    }

    /**
     * @param string $secret
     * @return string
     */
    private function filterValidateSecret($secret)
    {
        if (!is_string($secret) || !$secret) {
            throw new \InvalidArgumentException('Secret parameter should be a non-empty string');
        }

        return $secret;
    }

    /**
     * @param string $baseUri
     * @return string
     */
    private function filterValidateBaseUri($baseUri)
    {
        $baseUri = filter_var((string) $baseUri, FILTER_VALIDATE_URL);
        if (false === $baseUri) {
            throw new \InvalidArgumentException('Baseuri parameter should be a valid url');
        };

        return $baseUri;
    }

    /**
     * @param $timeout
     * @return int
     */
    private function filterValidateTimeout($timeout)
    {
        $timeout = (int) $timeout;
        if ($timeout <= 0) {
            throw new \InvalidArgumentException('Timeout parameter should be integer value greater than 0');
        }

        return $timeout;
    }

    /**
     * @param $sslVerify
     * @return bool
     */
    private function filterValidateSslVerify($sslVerify)
    {
        return (bool) $sslVerify;
    }
}