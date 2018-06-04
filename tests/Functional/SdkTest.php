<?php

namespace Test\Functional;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sarus\Config;
use Sarus\Request\Enrollment\GetList;
use Sarus\Request\User;
use Sarus\Response;
use Sarus\Sdk;
use Sarus\SdkFactory;

class SdkTest extends TestCase
{
    const USER_EMAIL = 'test@test.com';
    /**
     * @var Sdk
     */
    private $sdk;
    private $productUuid;

    protected function setUp()
    {
        $dotenv = new Dotenv(__DIR__ . '/config', '.env');
        $dotenv->load();
        $dotenv->required(['SARUS_URI', 'SARUS_SECRET', 'SARUS_PRODUCT_UUID']);

        $this->productUuid = getenv('SARUS_PRODUCT_UUID');

        $this->sdk = (new SdkFactory())->create(new Config(
            getenv('SARUS_SECRET'),
            getenv('SARUS_URI'),
            10,
            false
        ));
    }

    public function test_it_purchases_product()
    {
        $user = (new User(
            self::USER_EMAIL,
            'test_name',
            'test-last_name',
            96
        ))
            ->setCity('Milwaukee')
            ->setCountry('USA')
            ->setRegion('Wisconsin')
            ->setAddress1('53 Creek Lane')
            ->setPostalCode('53204')
        ;

        $products = [$this->productUuid];
        $response = $this->sdk->purchaseProduct($products, $user);
        self::assertInstanceOf(Response::class, $response);
    }

    public function test_it_successfully_returns_enrollment_data()
    {
        $response = $this->sdk->listEnrollments(self::USER_EMAIL);

        self::assertInstanceOf(Response::class, $response);

        $data = $response->get('data');

        static::assertArrayHasKey('course_uuid', $data[0]);
        static::assertArrayHasKey('title', $data[0]);
        static::assertArrayHasKey('description', $data[0]);
        static::assertArrayHasKey('image_src', $data[0]);
        static::assertArrayHasKey('url', $data[0]);
    }

    public function test_it_deactivates_enrollment()
    {
        $products = [$this->productUuid];
        $response = $this->sdk->deactivateEnrollments(self::USER_EMAIL, $products);
        self::assertInstanceOf(Response::class, $response);
    }

    public function test_it_unlinks_product()
    {
        $product = $this->productUuid;
        $response = $this->sdk->unlinkProduct($product);
        self::assertInstanceOf(Response::class, $response);
    }

    /**
     * @expectedException \Sarus\Client\Exception\HttpException
     */
    public function test_http_exception()
    {
        $this->sdk->handleRequest(new GetList('bla'));
    }
}