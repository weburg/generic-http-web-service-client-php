<?php
namespace unit;

require_once "weburg/ghowst/GenericHttpWebServiceClient.php";
require_once "weburg/ghowst/HttpWebServiceException.php";

use PHPUnit\Framework\TestCase;
use weburg\ghowst\GenericHttpWebServiceClient;
use weburg\ghowst\HttpWebServiceException;

class GenericHttpWebServiceClientTest extends TestCase {
    public function setUp(): void {
        $this->testWebService = new GenericHttpWebServiceClient("http://nohost/noservice");
    }

    public function testServiceException() {
        $this->expectException(HttpWebServiceException::class);

        $engine = new \stdClass();
        $engine->name = "PHPTestEngine";
        $engine->cylinders = 12;
        $engine->throttleSetting = 50;

        $this->testWebService->createEngines(engine: $engine);
    }
}
