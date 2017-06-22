<?php


//Includes ** //
include 'products.php';
/**
 * Path to Autoloader here, Usually in main directory. 
 */
 
require 'php-autoloader.php';

/**
 *
 * Ensure that you have edited the configuration.php file to include all the Auth Keys needed to complete the upload.
 *
 */
$config = require 'configuration.php';




/**
 * The namespaces provided by the SDK.
 */
 
use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Trading\Services;
use \DTS\eBaySDK\Trading\Types;
use \DTS\eBaySDK\Trading\Enums;


foreach($products_array as $imported) {
	$title = $imported['title'];
	$SKU = $imported['SKU'];
	$description = $imported['description'];
	$PictureURL1 = $imported['PictureURL1'];
	$PictureURL2 = $imported['PictureURL2'];
	$PictureURL3 = $imported['PictureURL3'];
	$Category = $imported['Category'];
	$Brand = $imported['Brand'];
	$MPN = $imported['MPN'];
	$EAN = $imported['EAN'];
	$Condition = $imported['Condition'];
	$Quantity = $imported['Quantity'];
	$Price = $imported['Price'];
	$Shipping1 = $imported['Shipping1'];
	$Shipping2 = $imported['Shipping2'];
	
	settype($title, 'string');
	settype($Condition, 'Integer');
	settype($Quantity, 'Integer');
	settype($Brand, 'string');
	settype($Price, 'double');
/**
 * Specify the numerical site id that we want the listing to appear on. EG, GB = UK Website US = United States Website
 *
 * This determines the validation rules that eBay will apply to the request.
 * For example, it will determine what categories can be specified, the values
 * allowed as shipping services, the visibility of the item in some searches and other
 * information.
 *
 */
 
 
$siteId = Constants\SiteIds::GB;

/**
 * Create the service object.
 */
$service = new Services\TradingService([
    'credentials' => $config['production']['credentials'],
    'sandbox'     => false,
    'siteId'      => $siteId
]);

/**
 * Create the request object.
 */
$request = new Types\AddFixedPriceItemRequestType();

/**
 * An user token is required when using the Trading service.
 */
$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
$request->RequesterCredentials->eBayAuthToken = $config['production']['authToken'];

/**
 * Begin creating the fixed price item.
 */
 
$item = new Types\ItemType();

/**
 * We want a multiple quantity fixed price listing.
 */
$item->ListingType = Enums\ListingTypeCodeType::C_FIXED_PRICE_ITEM;
$item->Quantity = $Quantity;

/**
 * Let the listing be automatically renewed every 30 days until cancelled.
 */
$item->ListingDuration = Enums\ListingDurationCodeType::C_GTC;

/**
 * The cost of the item is $19.99.
 * Note that we don't have to specify a currency as eBay will use the site id
 * that we provided earlier to determine that it will be United States Dollars (USD).
 */
$item->StartPrice = new Types\AmountType(['value' => $Price]);

/**
 * Allow buyers to submit a best offer.
 */
$item->BestOfferDetails = new Types\BestOfferDetailsType();
$item->BestOfferDetails->BestOfferEnabled = false;

/**
 * Automatically accept best offers of $17.99 and decline offers lower than $15.99.
 */
$item->ListingDetails = new Types\ListingDetailsType();
$item->ListingDetails->BestOfferAutoAcceptPrice = new Types\AmountType(['value' => 17.99]);
$item->ListingDetails->MinimumBestOfferPrice = new Types\AmountType(['value' => 15.99]);
/**
 * Provide a title and description and other information such as the item's location.
 * Note that any HTML in the title or description must be converted to HTML entities.
 */
$item->Title = $title;
$item->Description = $description;
$item->SKU = $SKU;
$item->Country = 'GB';
$item->Location = 'Halifax';
$item->PostalCode = 'HX62UX';


/** MPN and Brand Needed 
 *
 *
 */
$item->ProductListingDetails = new Types\ProductListingDetailsType();
$item->ProductListingDetails->UPC = 'Does not apply';
$item->ProductListingDetails->EAN = '5051259015555';
$item->ItemSpecifics = new Types\NameValueListArrayType();

$specific = new Types\NameValueListType();
$specific->Name = 'Brand';
$specific->Value[] = $Brand;
$item->ItemSpecifics->NameValueList[] = $specific;

$specific = new Types\NameValueListType();
$specific->Name = 'MPN';
$specific->Value[] = $MPN;
$item->ItemSpecifics->NameValueList[] = $specific;

/**
 * This is a required field.
 */
$item->Currency = 'GBP';

/**
 * Display a picture with the item.
 */
$item->PictureDetails = new Types\PictureDetailsType();
$item->PictureDetails->GalleryType = Enums\GalleryTypeCodeType::C_GALLERY;
$item->PictureDetails->PictureURL = [$PictureURL1, $PictureURL2];


/**
 * List item in the Allocated Category
 */
$item->PrimaryCategory = new Types\CategoryType();
$item->PrimaryCategory->CategoryID = $Category;


/**
 * Tell buyers what condition the item is in.
 * For the category that we are listing in the value of 1000 is for Brand New.
 */
 
$item->ConditionID = $Condition;

/**
 * Buyers can use one of two payment methods when purchasing the item.
 * Visa / Master Card
 * PayPal
 * The item will be dispatched within 1 business days once payment has cleared.
 * Note that you have to provide the PayPal account that the seller will use.
 * This is because a seller may have more than one PayPal account.
 */
$item->PaymentMethods = [
    'PayPal'
];
$item->PayPalEmailAddress = 'example@example.com';
$item->DispatchTimeMax = 1;

/**
 * Setting up the shipping details.
 * We will use a Flat shipping rate for both domestic and international.
 */
$item->ShippingDetails = new Types\ShippingDetailsType();
$item->ShippingDetails->ShippingType = Enums\ShippingTypeCodeType::C_FLAT;

/**
 * Create our first domestic shipping option.
 * Offer the Economy Shipping (1-10 business days) service at $2.00 for the first item.
 * Additional items will be shipped at $1.00.
 */
$shippingService = new Types\ShippingServiceOptionsType();
$shippingService->ShippingServicePriority = 1;
$shippingService->ShippingService = $Shipping1;
$shippingService->ShippingServiceCost = new Types\AmountType(['value' => 2.00]);
$shippingService->ShippingServiceAdditionalCost = new Types\AmountType(['value' => 1.00]);
$item->ShippingDetails->ShippingServiceOptions[] = $shippingService;

/**
 * Create our second domestic shipping option.
 */
$shippingService = new Types\ShippingServiceOptionsType();
$shippingService->ShippingServicePriority = 2;
$shippingService->ShippingService = $Shipping2;
$shippingService->ShippingServiceCost = new Types\AmountType(['value' => 3.00]);
$shippingService->ShippingServiceAdditionalCost = new Types\AmountType(['value' => 2.00]);
$item->ShippingDetails->ShippingServiceOptions[] = $shippingService;

/**
 * Create our first international shipping option.
 */
 
$shippingService = new Types\InternationalShippingServiceOptionsType();
$shippingService->ShippingServicePriority = 1;
$shippingService->ShippingService = 'UK_RoyalMailAirmailInternational';
$shippingService->ShippingServiceCost = new Types\AmountType(['value' => 4.00]);
$shippingService->ShippingServiceAdditionalCost = new Types\AmountType(['value' => 3.00]);
$shippingService->ShipToLocation = [
	'AU',
	'Europe'];
$item->ShippingDetails->InternationalShippingServiceOption[] = $shippingService;

/**
 * Create our second international shipping option.
 * The item will only be shipped to the following locations with this service.
 */
 
$shippingService = new Types\InternationalShippingServiceOptionsType();
$shippingService->ShippingServicePriority = 2;
$shippingService->ShippingService = 'UK_RoyalMailInternationalSignedFor';
$shippingService->ShippingServiceCost = new Types\AmountType(['value' => 5.00]);
$shippingService->ShippingServiceAdditionalCost = new Types\AmountType(['value' => 4.00]);
$shippingService->ShipToLocation = [
    'AU',
    'Europe'
];
$item->ShippingDetails->InternationalShippingServiceOption[] = $shippingService;

/**
 * The return policy.
 * Returns are accepted.
 * The buyer will pay the return shipping cost.
 */
$item->ReturnPolicy = new Types\ReturnPolicyType();
$item->ReturnPolicy->ReturnsAcceptedOption = 'ReturnsAccepted';
$item->ReturnPolicy->ReturnsWithinOption = 'Days_30';
$item->ReturnPolicy->ShippingCostPaidByOption = 'Buyer';

/**
 * Finish the request object.
 */
$request->Item = $item;

/**
 * Send the request.
 */
$response = $service->addFixedPriceItem($request);


/**
 * Output the result of calling the service operation.
 */
if (isset($response->Errors)) {
    foreach ($response->Errors as $error) {
        printf(
            "%s: %s\n%s\n\n <br/>",
            $error->SeverityCode === Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
            $error->ShortMessage,
            $error->LongMessage
        );
    }
}

if ($response->Ack !== 'Failure') {
	printf(
		$title, $SKU, "was listed to the eBay Sandbox/Production with the Item number %s \n"
	);
    printf(
        $response->ItemID
    );
}
}
