# HttpClient

### Description
HttpClient is a PHP HTTP client that makes it easy to send HTTP requests and integrate with web services.

### Installation

Run the following command on the terminal:
```
composer config repositories.httpClient git git@github.com:madeiramadeirabr/HttpClient.git
```
and
```
composer require madeiramadeirabr/http-client
```

## Usage

#### get()
**Parameters:**
  - string **$url**: address to be called
  - (optional) array **$headers**: the headers of the request (eg: ['cache-control' => 'no-cache'])
  - (optional) array **$options**: configuration of some properties of the request ([see options here](#filesystem))
    
**Return:** array|null
```
$client = new HttpClient();
$responseBody = $client->get('https://jsonplaceholder.typicode.com/posts');
```

#### post()
**Parameters:**
  - string **$url**: address to be called
  - array **$body**: the body of the request
  - (optional) array **$headers**: the headers of the request (eg: ['cache-control' => 'no-cache'])
  - (optional) array **$options**: configuration of some properties of the request ([see options here](#filesystem))
  
**Return:** array|null
```
$client = new HttpClient();
$responseBody = $client->post('https://jsonplaceholder.typicode.com/posts', [
    'title' => 'foo',
    'body' => 'bar',
    'userId' => 1
]);
```

#### put()
  - string **$url**: address to be called
  - array **$body**: the body of the request
  - (optional) array **$headers**: the headers of the request (eg: ['cache-control' => 'no-cache'])
  - (optional) array **$options**: configuration of some properties of the request ([see options here](#filesystem))
  
  **Return:** array|null
```
$client = new HttpClient();
$responseBody = $client->put('https://jsonplaceholder.typicode.com/posts', [
    'title' => 'foobar'
]);
```

#### delete()
**Parameters:**
  - string **$url**: address to be called
  - (optional) array **$headers**: the headers of the request (eg: ['cache-control' => 'no-cache'])
  - (optional) array **$options**: configuration of some properties of the request ([see options here](#filesystem))
    
**Return:** array|null
```
$client = new HttpClient();
$responseBody = $client->get('https://jsonplaceholder.typicode.com/posts');
```

#### request()

This method can perform a request using any method available in the package (GET, POST, PUT, DELETE).
The difference for the methods above is the return. This method returns an HttpResponse object.

**Parameters:**
  - string **$method**: The method of the request
  - string **$url**: address to be called
  - (optional) array **$headers**: the headers of the request (eg: ['cache-control' => 'no-cache'])
  - (optional) array **$options**: configuration of some properties of the request ([see options here](#filesystem))
    
**Return:** array|null
```
$client = new HttpClient();
$responseBody = $client->get('https://jsonplaceholder.typicode.com/posts');
```

## Configuration

There are some configurations you can use to customize your calls. Some of the customizations are:

- Headers
- Curl parameters
- Body handlers
- General settings
- Events (see [Events Section](#Events))

It can happen in the object instantiation:
```
new HttpClient(
    ?string $baseUrl = null,
    ?array $headers = null,
    ?array $options = null,
    ?IBodyHandler $requestBodyHandler = null,
    ?IBodyHandler $responseBodyHandler = null)
```
Using setters:
```
$client = new HttpClient();
$client->setHeaders(array $headers);
$client->setOptions(array $options);
```

Set for each request:
```
$client = new HttpClient();
$client->get('https://foo.bar', array $headers, array $options);
```

###Headers

Any header can be set (see all headers: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers).
The array of the headers is **case insensitive**.
Example:

````
$headers = [
    'cache-control' => 'no-cache',
    'content-type' => 'application/json; charset=utf-8'
];
new HttpClient($baseUrl, $headers);
````

###Curl parameters

We can set the value of some Curl options. The configurable options are:

 - CURLOPT_MAXREDIRS
 - CURLOPT_CONNECTTIMEOUT
 - CURLOPT_DNS_CACHE_TIMEOUT
 - CURLOPT_HTTP_VERSION
 - CURLOPT_MAXCONNECTS
 - CURLOPT_FOLLOWLOCATION
 - CURLOPT_POSTREDIR
 - CURLOPT_TIMEOUT
 - CURLOPT_TIMEOUT_MS
 - CURLOPT_COOKIE
 - CURLOPT_USERAGENT
 
 The documentation of these options: https://www.php.net/manual/en/function.curl-setopt.php
 
 You can use the property _curlSettings_ in the $options array to set these values. Example:
 
 ````
$options = [
    'curlSettings' => [
        CURLOPT_CONNECTTIMEOUT => 1
    ]
];

new HttpClient($baseUrl, $headers, $options);
 ````
 
 ###Body handler
 
 It is possible to set how the body can be handled. 
 
 The interface IBodyHandler has the method _encode()_, which encodes the request body to be sent
 and the method _decode()_, which decodes the body of the response.
 There are 2 BodyHandlers implemented by default:
 
 - JsonBodyHandler
 - FormBodyHandler
 
 By default, the package uses the JsonBodyHandler, but you can create a new one and set in the object. 
 
 The way you can set your custom IBodyHandler is:
 
 ````
$customBodyHandlerForRequest = new myCustomRequestHandler();
$customerBodyHandlerForResponse = new myCustomResponseHandler();

new HttpClient(
    $baseUrl, 
    $headers, 
    $options, 
    $customBodyHandlerForRequest, 
    $customerBodyHandlerForResponse);
 ````

###General Settings

You can configure these properties too:

| Property | Description | Value type |
| --- | --- | --- |
| `baseUrl` | The base url which is concatenated with the URL passed in the request | string |
| `curlSettings` | An array with all Curl properties you want to configure  | int[] |
| `slowRequestTime` | Set the time in seconds which the request will be considered slow  | int |
| `requestBodyHandler` | The handler of the request body | IBodyHandler  |
| `responseBodyHandler` | The handler of the response body | IBodyHandler |
| `responseStatusWhitelist` | An array which can be used to overwrite the property _unexpectedStatus_  | int[] |
| `unexpectedStatus` | Set all status which are considered unexpected to the request | int[] |

### Events

The package analysis each response to check if some events occur. 
It uses [madeiramadeirabr/event-observer package](https://github.com/madeiramadeirabr/event-observer) 
to dispatch events. The events are:

| Event | Description | Object dispatched |
| --- | --- | --- |
| `HTTP_CLIENT_SLOW_REQUEST_ALERT` | If the request takes longer than a defined parameter | ITransaction |
| `HTTP_CLIENT_FALSE_POSITIVE_STATUS_ALERT` | If the response status is 200, the body is not empty, and the body handler returns null when calling the decode method, this event will be triggered  | ITransaction |
| `HTTP_CLIENT_UNEXPECTED_RESPONSE_STATUS_ALERT` | If the array _unexpectedStatus_ is set and the response status is in this array  | ITransaction |
| `HTTP_CLIENT_CURL_ERROR_ALERT` | If the Curl lib returns an error  | ITransaction  |

You can implement a listener for these events in your application. 
To do this you can create a new class in your project and implements HttpRequests\ObserverInterface.
Example:

````
<?php

namespace Server\Application\Observer\HttpRequests;

use MadeiraMadeiraBr\Event\ObserverInterface;

class UnexpectedResponseStatusObserver implements ObserverInterface
{
    /**
     * Get execution priority
     *
     * @return integer
     */
    public function getPriority(): int
    {
        return 1;
    }

    /**
     * Receive update from subject
     * @link https://php.net/manual/en/splobserver.update.php
     * @param SplSubject $subject <p>
     * The <b>SplSubject</b> notifying the observer of an update.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function update(SplSubject $subject)
    {
        $transaction = $subject->getEvent();
        //do something here
    }
}
````

The update method receives the object dispatched in the event.

### Mocks

The package contains a helper to mock the http requests.
It can be used in your tests to mock API's responses. 
Example:

````
public function testGetOneProduct()
{
    MockHandler::getInstance()->add(new Mock(
        'GET',
        'https://api.com.br/v1/product/123',
        self::STUB_FOLDER . 'HttpResponse/Product/get-product-123.json'));

    $product = ProductService::get(123);
    $this->assertArrayHasKey('main', $product);
    $this->assertArrayHasKey('flags', $product);
    $this->assertArrayHasKey('taxonomy', $product);
    $this->assertArrayHasKey('buyBox', $product);
    $this->assertArrayHasKey('attributes', $product);
    $this->assertArrayHasKey('images', $product);
    $this->assertArrayHasKey('breadcrumb', $product);
    $this->assertArrayHasKey('variants', $product);
    $this->assertArrayHasKey('extendedWarranty', $product);
    $this->assertArrayHasKey('complementaryProducts', $product);
    $this->assertArrayHasKey('kit', $product);
    $this->assertArrayHasKey('ensemble', $product);
}
````

In this example it creates a mock to the endpoint _GET https://api.com.br/v1/product/123_.
If your application requests this endpoint, it will return the mock which was passed to the MockHandler.

