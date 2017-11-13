# Verify client

![](https://habrastorage.org/webt/59/d5/42/59d542206afbe280817420.png)

This is a client library which encapsulates interaction with [Jincor Verify](https://github.com/JincorTech/backend-verufy). With its help you can:
1. Initiate verification process for methods: email, google_auth.
2. Validate code.
3. Invalidate code.

Usage
-----
### Initialize Verify client
To interact with the HTTP protocol use [Guzzle](https://github.com/guzzle/guzzle). Headers `Accept: application/vnd.jincor+json; version=1` , `Content-Type: application/json`, `Authorization: Bearer JWT_TOKEN` are mandatory.

```php
$verifyClient = new VerifyClient(new Client([
    'base_uri' => 'verify:3000',
    'headers' => [
        'Accept'        => 'application/vnd.jincor+json; version=1',
        'Content-Type'  => 'application/json',
        'Authorization' => 'Bearer JWT_TOKEN',
    ]
]));
```

### Initiate Verification process
```php
$verificationDetails = $verifyClient->initiate(
    (new EmailVerification())
        ->setTemplate('{{{CODE}}}')
        ->setConsumer('test@test.com')
        ->setExpiredOn('01:00:00')
);
```

### Validate Code

```php
$result = $verifyClient->validate(new EmailValidationData(
    new Uuid('d6b78279-db85-467e-b965-c938d043ffac'),
    '123456'
));
// true
```

### Invalidate Code

```php
$result = $verifyClient->invalidate(new GoogleAuthInvalidationData(
    new Uuid('d6b78279-db85-467e-b965-c938d043ffab')
));
// true
```

More details can be received in the tests.

### Project setup
1. Clone the repo
2. `cd /path/to/repo`
3. `docker-compose build` - build development containers
4. `docker-compose up -d` - run container
5. `docker-compose exec workspace composer install`

#### Local testing
To run all tests just type `docker-compose exec workspace ./vendor/bin/codecept run`

Credits
-------
* [Aleserche](https://github.com/Aleserche)
* [Jincor Team](https://jincor.com)

License
-------
The MIT License (MIT). Please see [License File](LICENSE) for more information.