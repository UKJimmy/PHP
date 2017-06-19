<?php

/**
 * PHP AUTOLOADER PATH GOES HERE :-)
 */
require 'php-autoloader.php';

/**
 *
 *Configuration File path
 *
 */
$config = require 'configuration.php';

/**
 * The Function paths.
 */

use \DTS\eBaySDK\Inventory\Services;
use \DTS\eBaySDK\Inventory\Types;
use \DTS\eBaySDK\Inventory\Enums;

/**
 * Create the service object.
 */
$service = new Services\InventoryService([
    'authorization'    => $config['production']['oauthUserToken'],
    'requestLanguage'  => 'en-UK',
    'responseLanguage' => 'en-UK',
    'sandbox'          => false
]);

/**
 * Create the request object.
 */
$request = new Types\CreateOrReplaceInventoryItemRestRequest();

/**
 * URI parameters are just properties on the request object.
 */
$request->sku = '123';

$request->availability = new Types\Availability();
$request->availability->shipToLocationAvailability = new Types\ShipToLocationAvailability();
$request->availability->shipToLocationAvailability->quantity = 1;

$request->condition = Enums\ConditionEnum::C_NEW_OTHER;

$request->product = new Types\Product();
$request->product->title = 'Microphone stand - VisioSound';
$request->product->description = 'The heavy big white box with the microphone stand in it';
/**
 * Aspects are specified as an associative array.
 */
$request->product->aspects = [
    'Brand'                => ['VisioSound'],
    'Type'                 => ['Microphone Stand'],
    'Storage Type'         => ['Not really sure'],
];
$request->product->imageUrls = [
    'http://prodimage.images-bn.com/pimages/9781338099133_p0_v5_s1200x630.jpg'
];

/**
 * Send the request.
 */
$response = $service->createOrReplaceInventoryItem($request);

/**
 * Output the result of calling the service operation.
 */
printf("\nStatus Code: %s\n\n", $response->getStatusCode());
if (isset($response->errors)) {
    foreach ($response->errors as $error) {
        printf(
            "%s: %s\n%s\n\n",
            $error->errorId,
            $error->message,
            $error->longMessage
        );
    }
}

if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 400) {
    echo "Success\n";
}
