# Generic HTTP Web Service Client in PHP (GHoWSt)

## A client written to talk to the Generic HTTP Web Service Server

### Design goals

- Use local language semantics to talk to the server dynamically. The only thing
  required are the ghowst classes and cURL enabled/installed.
- Every call, using a method name convention to map to HTTP methods, gets
  translated to HTTP requests. Responses are parsed from JSON and mapped back to
  local objects.

### Example code

```php
require_once "weburg/ghowst/GenericHttpWebServiceClient.php";

use weburg\ghowst\GenericHttpWebServiceClient;

$httpWebService = new GenericHttpWebServiceClient("http://localhost:8081/generichttpws");

// Create
$engine = new stdClass();
$engine->name = "PHPZendEngine";
$engine->cylinders = 44;
$engine->throttleSetting = 49;
$engineId1 = $httpWebService->createEngines($engine);
```

### Running the example

First, ensure the server is running. Refer to other grouped GHoWSt projects to
get and run the server. Ensure PHP 8 or better is installed. Then, make sure
cURL is enabled in your php.ini. If on Linux, you may also have to install, for
example, the php-curl package in addition to php-cli and optionally php-xdebug.

If using the CLI, ensure you are in the project directory. Run:

`php run_example_generic_http_web_service_client.php`

If using an IDE, you should only need to run the below file:

`run_example_generic_http_web_service_client.php`

The example runs several calls to create, update, replace, read, delete, and do
a custom action on resources.