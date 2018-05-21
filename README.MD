# sarus-sdk-php
Sarus SDK for PHP

## Install

The recommended way to install riselms/sarus-sdk-php is [through composer](http://getcomposer.org).

```bash
composer require riselms/sarus-sdk-php
```

## Usage

Don't forget to autoload:

```php
<?php
require 'vendor/autoload.php';
```

### Initialize Sdk object:

```php
use Sarus\SdkFactory;
use Sarus\Config;

$config = new Config(
   'secret',                // Requred  - API secret for sarus    
   'https://api.sarus.io', // Optional - base url
   30,                     // Optional - timeout
   true                    // Optional - ssl verify
);
$factory = new SdkFactory();
$sdk = $factory->create($config);
 
// If you want to log request and response:
 $logger = new Logger();
 $logLevel = LogLevel::INFO; //By default log level is info
 $sdk = $factory->createWithLogger($config, $logger, $logLevel);
```

### Purchase product:
```php

$sarusProductUuids = ['uuid1', 'uuid2'];
$user = new User(
    'test@test.com',
    'test_name',
    'test-last_name',
    96
);
$user
    ->setCity('Milwaukee')
    ->setCountry('USA')
    ->setRegion('Wisconsin')
    ->setAddress1('53 Creek Lane')
    ->setPostalCode('53204')
;
try {

    $sdk->purchaseProduct($sarusProductUuids, $user)

} catch (\Sarus\Client\Exception\HttpException $e) {
}
```

### Unlink product:

```php

$sarusPoductUuid = 'uuid1';
try {

    $sdk->unlinkProduct($sarusPoductUuid)

} catch (\Sarus\Client\Exception\HttpException $e) {
}
```

### Get list of courses where user is enrolled:
```php
$email = 'testemal@user.com';
try {

    $enrollments = $sdk->listEnrollments($email);
    
    foreach($enrollments as $enrollment) {
        $enrollment['course_uuid'];
        $enrollment['title'];
        $enrollment['description'];
        $enrollment['image_src'];
        $enrollment['url'];
    }

} catch (\Sarus\Client\Exception\HttpException $e) {
}
```

### Deactivate enrollment of a user:
```php
$email = 'testemal@user.com';
$sarusPoductUuids = ['uuid1', 'uuid2'];
try {

    $sdk->deactivateEnrollments($email, $sarusPoductUuids);

} catch (\Sarus\Client\Exception\HttpException $e) {
}
```

#### Information which could be taken from Sarus\Client\Exception\HttpException:
```php
try {

// request

} catch (\Sarus\Client\Exception\HttpException $e) {
    // The exceptions is thrown if http request is not successful

    $e->getMessage();           // returns either grabbed message from response body or reason phrase
    $e->getStatusCode();        // returns http status code of the response
    $e->hasResponse();          // returns bool, if exception holds response object
    $e->getResponse();          // returns \Psr\Http\Message\ResponseInterface
    $e->getRequest();           // returns  \Psr\Http\Message\RequestInterface
}
```

#### Run tests

Note: these tests are functional tests, so they make real requests to API host

1. Create .env file by copy .env.example in tests/config directory
2. Specify values described in .env file
3. Run tests