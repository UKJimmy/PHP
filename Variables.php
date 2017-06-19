<?php

/** Variables used in the Test File to upload.
 * Each item uploaded to the eBay website requires different validation rules from the API when sending requests. 
 * To Ensure each item is uploaded successfully we need to make sure each and every validation is filled when an item is uploaded.
 * For example; Every item needs the Brand Description. 
 *
 *
 **/

$item->ListingType = Enums\ListingTypeCodeType::C_FIXED_PRICE_ITEM; #Creates a fixed price listing.
$item->Quantity = ; #Quantity of item to be added to listing
$item->ListingDuration = Enums\ListingDurationCodeType::C_GTC; #Listing Duration
$item->StartPrice = new Types\AmountType(['value' => 19.99]); #Starting Price

/**
  *Basic attributes below. 
  *
  *
  */

$item->Title = ''; #Title of Listing
$item->Description = ''; #Description for listing HTML tags can be used.
$item->SKU = ''; 
$item->Country = '';
$item->Location = '';
$item->PostalCode = '';

/** Brand and MPN
 *
 *
 */
$item->ProductListingDetails = new Types\ProductListingDetailsType();
$item->ProductListingDetails->UPC = 'Does not apply';

$item->ItemSpecifics = new Types\NameValueListArrayType();

$specific = new Types\NameValueListType();
$specific->Name = '';
$specific->Value[] = '';
$item->ItemSpecifics->NameValueList[] = $specific;

$specific = new Types\NameValueListType();
$specific->Name = '';
$specific->Value[] = '';
$item->ItemSpecifics->NameValueList[] = $specific;

/* Currency is required with every listing */
$item->Currency = 'GBP';

/*Adding pictures to the listing */
$item->PictureDetails = new Types\PictureDetailsType();
$item->PictureDetails->GalleryType = Enums\GalleryTypeCodeType::C_GALLERY;
$item->PictureDetails->PictureURL = [''];
/* Category IDS */
$item->PrimaryCategory = new Types\CategoryType();
$item->PrimaryCategory->CategoryID = '26261';
/* Payment Methods */
$item->PaymentMethods = [
    'PayPal'
];
$item->PayPalEmailAddress = 'sales@visiosound.co.uk';
/* Dispatch Time in Days */
$item->DispatchTimeMax = 1;
/**
 *
 * All variables for different shipping options will have to be defined seperately including price of each item. 
 *
 *
 *
 */
$shippingService = new Types\ShippingServiceOptionsType();
$shippingService->ShippingServicePriority = 1;
$shippingService->ShippingService = 'UK_RoyalMailSecondClassStandard';
$shippingService->ShippingServiceCost = new Types\AmountType(['value' => 2.00]);
$shippingService->ShippingServiceAdditionalCost = new Types\AmountType(['value' => 1.00]);
$item->ShippingDetails->ShippingServiceOptions[] = $shippingService;


