<?php
/**
 * Include the SDK by using the autoloader from Composer.
 */
require 'php-autoloader.php';

/**
 * Include the configuration values.
 *
 * Ensure that you have edited the configuration.php file
 * to include your application keys.
 */
$config = require 'configuration.php';

/**
 * The namespaces provided by the SDK.
 */
use \DTS\eBaySDK\Inventory\Services;
use \DTS\eBaySDK\Inventory\Types;
use \DTS\eBaySDK\Inventory\Enums;

/**
 * Create the service object.
 */
$service = new Services\InventoryService([
    'authorization' => $config['production']['oauthUserToken']
]);

/**
 * Create the request object.
 */
$request = new Types\GetInventoryItemRestRequest();

/**
 * Note how URI parameters are just properties on the request object.
 */
$request->sku = '123';

/**
 * Send the request.
 */
$response = $service->getInventoryItem($request);

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

if ($response->getStatusCode() === 200) {
    $product = $response->product;
    printf(
        "(%s) %s: %s\n",
        $response->sku,
        $product->title,
        $product->description
    );

    foreach ($product->aspects as $name => $values) {
        printf(
            "%s: %s\n",
            $name,
            implode(', ', $values)
        );
    }

    foreach ($product->imageUrls as $url) {
        printf("%s \n", $url);
    }
}