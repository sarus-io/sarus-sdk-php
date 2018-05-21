<?php

namespace Sarus;

use PHPUnit\Framework\TestCase;

class ListEnrollmentsTest extends TestCase
{
    /**
     * @var Sdk
     */
    private $sdk;

    protected function setUp()
    {
        if (!getenv('SARUS_URI') || !getenv('SARUS_SECRET')) {
            $this->markTestSkipped('You should provide SARUS_URI and SARUS_SECRET in phpunit.xml to run tests');
        }

        $this->sdk = (new SdkFactory(
            getenv('SARUS_SECRET'),
            getenv('SARUS_URI'),
            10,
            false
        ))->create();
    }

    public function test_it_successfully_returns_enrollment_data()
    {
        $data = $this->sdk->listEnrollments('info@riselms.com');
        static::assertNotEmpty($data);
    }

    /**
     * @expectedException \Sarus\Client\Exception\HttpException
     */
    public function test_http_exception()
    {
        $data = $this->sdk->listEnrollments('bla');
        static::assertNotEmpty($data);
    }
}