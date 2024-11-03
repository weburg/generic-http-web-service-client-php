<?php
require_once "weburg/ghowst/GenericHttpWebServiceClient.php";

use weburg\ghowst\GenericHttpWebServiceClient;

$httpWebService = new GenericHttpWebServiceClient("http://whale:8081/generichttpws");

/*** Photo ***/

// Create
$photo = new stdClass();
$photo->caption = "Some PHP K";
$photo->photoFile = new CURLFile("PHP_Logo.png");
$httpWebService->createPhotos(photo: $photo);

/*** Engine ***/

// Create
$engine = new stdClass();
$engine->name = "PHPZendEngine";
$engine->cylinders = 44;
$engine->throttleSetting = 49;
$engineId1 = $httpWebService->createEngines(engine: $engine);

// CreateOrReplace (which will create)
$engine = new stdClass();
$engine->id = -1;
$engine->name = "PHPZendEngineCreatedNotReplaced";
$engine->cylinders = 45;
$engine->throttleSetting = 50;
$httpWebService->createOrReplaceEngines(engine: $engine);

// Prepare for CreateOrReplace
$engine = new stdClass();
$engine->name = "PHPZendEngine2";
$engine->cylinders = 44;
$engine->throttleSetting = 49;
$engineId2 = $httpWebService->createEngines(engine: $engine);

// CreateOrReplace (which will replace)
$engine = new stdClass();
$engine->id = $engineId2;
$engine->name = "PHPZendEngine2Replacement";
$engine->cylinders = 56;
$engine->throttleSetting = 59;
$httpWebService->createOrReplaceEngines(engine: $engine);

// Prepare for Update
$engine = new stdClass();
$engine->name = "PHPZendEngine3";
$engine->cylinders = 44;
$engine->throttleSetting = 49;
$engineId3 = $httpWebService->createEngines(engine: $engine);

// Update
$engine = new stdClass();
$engine->id = $engineId3;
$engine->name = "PHPZendEngine3Updated";
$httpWebService->updateEngines(engine: $engine);

// Get
$engine = $httpWebService->getEngines(id: $engineId1);
echo "Engine returned: " . $engine->name . "\n";

// Get all
$engines = $httpWebService->getEngines();
echo "Engines returned: " . count($engines) . "\n";

// Prepare for Delete
$engine = new stdClass();
$engine->name = "PHPZendEngine4ToDelete";
$engine->cylinders = 89;
$engine->throttleSetting = 70;
$engineId4 = $httpWebService->createEngines(engine: $engine);

// Delete
$httpWebService->deleteEngines(id: $engineId4);

// Custom verb
$httpWebService->restartEngines(id: $engineId2);
?>