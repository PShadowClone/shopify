<?php

use GuzzleHttp\Client;
//die($config['ACCOUNT']['API_KEY']);
$shop = $config['ACCOUNT']['STORE_URL'];
$api_key = $config['ACCOUNT']['API_KEY'];
$password = $config['ACCOUNT']['API_TOKEN'];

$client = new Client([
    'base_uri' => "https://$api_key:$password@$shop/admin/api/2023-01/",
    'verify' => false   // Disable SSL verification just for development purpose
]);

/**
 * fetch the products from the given store
 * @param $client
 * @return array
 */
function fetchProducts($client) {
    $endpoint = 'products.json';
    $products = [];
    $params = [
        'limit' => 250
    ];

    do {
        $response = $client->get($endpoint, ['query' => $params]);
        $data = json_decode($response->getBody()->getContents(), true);

        if (isset($data['products']) && count($data['products']) > 0) {
            $products = array_merge($products, $data['products']);
            $params['page_info'] = getNextPageInfo($response);
        } else {
            break;
        }
    } while ($params['page_info']);

    return $products;
}

/**
 * fetch the products per page
 * @param $response
 * @return mixed|null
 */
function getNextPageInfo($response) {
    $header = $response->getHeader('Link');
    if (isset($header[0]) && preg_match('/<([^>]+)>; rel="next"/', $header[0], $matches)) {
        parse_str(parse_url($matches[1], PHP_URL_QUERY), $query);
        return $query['page_info'] ?? null;
    }
    return null;
}

function fetchLocations($client) {
    $endpoint = 'locations.json';
    $response = $client->get($endpoint);
    $data = json_decode($response->getBody()->getContents(), true);

    return $data['locations'] ?? [];
}


$location = fetchLocations($client);

/**
 * method calling
 */
$products = fetchProducts($client);

/**
 * count of the fetched products
 */
echo 'Total Products: ' . count($products)  . "<br/>";

/**
 * convert the fetched products to Product object
 */
$collection = [];
foreach ($products as $product) {
    $newProduct = new \Amr\Shopify\Models\Product();
    $newProduct->fill($product , CLEAN_OBJECT);
    $newProduct->setTitle('-nullable');
    $collection[] = $newProduct->getData();
    $newProduct->updateInventoryLevel($client ,$location[0]['id'], 50);
    echo 'Product ID: ' . $product['id'] . ', Title: ' . $product['title'] . "<br/>";
}
/**
 * save the fetched and cleaned object in the fetched_products.json file, so you can easily check the null values
 */
file_put_contents('storage/fetched_products.json' , json_encode($collection) , FILE_APPEND);
