<?php

namespace Sarus;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Sarus\Request\User;

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

        $this->sdk = (new SdkFactory(
            getenv('SARUS_SECRET'),
            getenv('SARUS_URI'),
            10,
            false
        ))->create();
    }

    /**
     * @doesNotPerformAssertions
     */
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
        $this->sdk->purchaseProduct($products, $user);

    }

    public function test_it_successfully_returns_enrollment_data()
    {
        $data = $this->sdk->listEnrollments(self::USER_EMAIL);
        static::assertArrayHasKey('course_uuid', $data[0]);
        static::assertArrayHasKey('title', $data[0]);
        static::assertArrayHasKey('description', $data[0]);
        static::assertArrayHasKey('image_src', $data[0]);
        static::assertArrayHasKey('url', $data[0]);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function test_it_deactivates_enrollment()
    {
        $products = [$this->productUuid];
        $this->sdk->deactivateEnrollments(self::USER_EMAIL, $products);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function test_it_unlinks_product()
    {
        $product = $this->productUuid;
        $this->sdk->unlinkProduct($product);
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