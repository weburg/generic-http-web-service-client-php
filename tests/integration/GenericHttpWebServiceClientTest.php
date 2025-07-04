<?php
namespace integration;

require_once "weburg/ghowst/GenericHttpWebServiceClient.php";

use PHPUnit\Framework\TestCase;
use weburg\ghowst\GenericHttpWebServiceClient;

class GenericHttpWebServiceClientTest extends TestCase {
    private $testWebService;

    public function setUp(): void {
        $this->testWebService = new GenericHttpWebServiceClient("http://localhost:8081/generichttpws");
    }

    public function testExampleGenericHttpWebServiceClient() {
        exec("php run_example_generic_http_web_service_client.php", $output, $return);

        foreach ($output as $line) {
            echo $line . "\n";
        }

        $this->assertEquals(0, $return);
    }

    public function testCreateEngine() {
        $engine = new \stdClass();
        $engine->name = "PHPTestEngine";
        $engine->cylinders = 12;
        $engine->throttleSetting = 50;

        $engineId = $this->testWebService->createEngines(engine: $engine);

        $this->assertTrue($engineId > 0);
    }
}