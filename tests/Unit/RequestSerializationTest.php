<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Sarus\Request\Enrollment\Deactivate;
use Sarus\Request\Enrollment\GetList;
use Sarus\Request\Product\Purchase;
use Sarus\Request\Product\Unlink;
use Sarus\Request\User;

class RequestSerializationTest extends TestCase
{
    /**
     * @dataProvider requestsDataProvider
     */
    public function test_serialization($request)
    {
        $serialized = serialize($request);
        $unserialized = unserialize($serialized);

        static::assertEquals($request->getBody(),   $unserialized->getBody());
        static::assertEquals($request->getPath(),   $unserialized->getPath());
        static::assertEquals($request->getMethod(), $unserialized->getMethod());
    }

    public function requestsDataProvider()
    {
        return [
            0 => [
                new Unlink('product_uuid')
            ],

            1 => [
                call_user_func(function() {
                    $user = (new User(
                        'test@test.com',
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

                    $products = ['product_uuid1', 'product_uuid2'];
                    $extraData = ['company_products' => ['product_uuid2']];
                    return new Purchase($products, $user, $extraData);
                })
            ],
            2 => [
                new Deactivate('test@test.com', $products = ['product_uuid1', 'product_uuid2']),
            ],
            3 => [
                new GetList('test@test.com'),
            ],
        ];
    }
}