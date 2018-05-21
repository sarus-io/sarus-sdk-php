<?php

namespace Sarus;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Sarus\Client\JsonGuzzleClient;

class SdkFactory
{
    /**
     * @return Sdk
     */
    public function create(Config $config)
    {
        return new Sdk(new JsonGuzzleClient(
            $this->createGuzzleClient($config)
        ));
    }

    public function createWithLogger(
        Config $config,
        LoggerInterface $logger,
        $logLevel = LogLevel::INFO
    ) {
        $handler = HandlerStack::create();
        $handler->push(Middleware::log($logger, new MessageFormatter(), $logLevel));

        return new Sdk(new JsonGuzzleClient(
            $this->createGuzzleClient($config, $handler)
        ));
    }

    /**
     * @param Config $config
     * @return \GuzzleHttp\Client
     */
    protected function createGuzzleClient(Config $config, HandlerStack $handler = null)
    {
        $config = $this->createGuzzleConfig($config);

        if ($handler) {
            $config['handler'] = $handler;
        }

        return new \GuzzleHttp\Client($config);
    }

    /**
     * @param Config $config
     * @return array|Config
     */
    protected function createGuzzleConfig(Config $config)
    {
        $config =  [
            'base_uri'        => $config->getBaseUri(),
            'timeout'         => $config->getTimeout(),
            'verify'          => $config->isSslVerify(),
            'allow_redirects' => false,
            'cookies'         => false,
            'headers' => [
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $config->getSecret()
            ]
        ];

        return $config;
    }
}