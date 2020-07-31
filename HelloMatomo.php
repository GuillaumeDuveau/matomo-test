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

$bulkRequests = [
  [
    'period' => 'day',
    'date' => 'today',
    'method' => 'Actions.getPageUrl',
    'pageUrl' => '/fr/vente/chateau-12000-1277',
  ],
  [
    'period' => 'day',
    'date' => 'today',
    'method' => 'UserCountry.getCity',
    'segment' => 'pageUrl=@/fr/vente/chateau-12000-1277',
  ],
];
$urls = [];
foreach ($bulkRequests as $bulkRequest) {
  $bulkRequest['idSite'] = 2;
  $urls[] = http_build_query($bulkRequest);
}

$response = $client->request(
  'POST',
  '', [
    'query' => [
      'module' => 'API',
      'format' => 'json',
      'token_auth' => 'db1c7ef8a962ad64baf481145c7dc938',
      'method' => 'API.getBulkRequest',
      'urls' => $urls,
    ],
  ]
);

if ($response->getStatusCode() === 200) {
  $bulkResults = json_decode($response->getBody()->getContents());
  foreach ($bulkResults as $results) {
    foreach ($results as $result) {
      dump($result);
      // print $result->label . "\n";
    }
  }
}
