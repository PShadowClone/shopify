<?php

use GuzzleHttp\Client;


$shop = $config['ACCOUNT']['STORE_URL'];
$api_key = $config['ACCOUNT']['API_KEY'];
$password = $config['ACCOUNT']['API_TOKEN'];

// read all products from the given csv file
$productsAll = read_csv('storage/products_export.csv', \Amr\Shopify\Models\Product::class);
// initialize http client
$clientStore = new Client([
    'base_uri' => "https://$shop",
    'auth' => [$api_key, $password],
    'verify' => !!$config['ACCOUNT']['VERIFY_SSL']  // Disable SSL verification just for development purpose
]);
//upload all given products
foreach ($productsAll as $pro) {
    $pro->save($clientStore);
}
