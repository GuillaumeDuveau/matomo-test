<?php

/**
 * @file
 * Test.
 */

require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;

$client = new Client([
  'base_uri' => 'https://analytics.doorinsider.com/',
]);

$response = $client->request(
  'POST',
  '', [
    'query' => [
      'module' => 'API',
      'format' => 'json',
      'token_auth' => 'db1c7ef8a962ad64baf481145c7dc938',
      'idSite' => 2,
      'period' => 'day',
      'date' => 'today',
      'method' => 'Actions.getPageUrl',
      'pageUrl' => '/fr/vente/chateau-12000-1277',
    ],
  ]
);

if ($response->getStatusCode() === 200) {
  $results = json_decode($response->getBody()->getContents());
  foreach ($results as $result) {
    dump($result);
    print $result->label . "\n";
  }
}
