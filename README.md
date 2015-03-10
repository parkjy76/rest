# simple rest libray for PHP
* server, client
* support GET, POST, PUT and DELETE methods

## Examples
+ client
```php
<?php
$url = 'https://test.com/v1/item/_search';
$data = ['size' => 1];
$data = json_encode($data);

echo "<<Data>>" . PHP_EOL;
var_dump($data);
echo PHP_EOL;

$rest = new RestClient;
$rest->setContentType('application/json; charset=UTF-8');
$rest->setAccept('application/json; charset=UTF-8');
$rest->setAuth('test','12345');
$rest->open();

echo "<<Status>>" . PHP_EOL;
var_dump($rest->write('POST', $url, $data));
echo PHP_EOL;

$result = $rest->read();
echo "<<Result>>" . PHP_EOL;
var_dump($result);

$rest->close();
```

+ server
```php
<?php
$server = new RestServer;
$method = $server->getRequestMethod();
$requestContext = $server->getRequestContext();
$responseContext = $server->getResponseContext();
$remoteAddress = $server->getRemoteAddress();
$data = $server->read(new RestMethod(new RestMethod_POST)); // method is 'POST'
```
