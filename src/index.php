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

function getNextPageInfo($response) {
    $header = $response->getHeader('Link');
    if (isset($header[0]) && preg_match('/<([^>]+)>; rel="next"/', $header[0], $matches)) {
        parse_str(parse_url($matches[1], PHP_URL_QUERY), $query);
        return $query['page_info'] ?? null;
    }
    return null;
}

$products = fetchProducts($client);

echo 'Total Products: ' . count($products)  . "<br/>";

foreach ($products as $product) {
    echo 'Product ID: ' . $product['id'] . ', Title: ' . $product['title'] . "<br/>";
}
