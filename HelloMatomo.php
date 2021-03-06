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
    'date' => '2020-08-01',
    'method' => 'Actions.get',
    'segment' => 'pageUrl=@-1278',
  ],
  [
    'period' => 'day',
    'date' => '2020-08-01',
    'method' => 'Actions.getPageUrl',
    'pageUrl' => '/fr/vente/appartement-91160-1278',
  ],
];
$urls = [];
foreach ($bulkRequests as $bulkRequest) {
  $bulkRequest['idSite'] = 1;
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
  dump($bulkResults);
}
