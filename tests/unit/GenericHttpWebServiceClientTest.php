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

    public function testCreateTestResource() {
        $this->expectException(HttpWebServiceException::class);

        $this->testWebService->createResource(resource: new \stdClass());
    }
}
