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
    'date' => '2020-01-01,today',
    'method' => 'Actions.getPageUrls',
  ],
];
$urls = [];
foreach ($bulkRequests as $bulkRequest) {
  $bulkRequest['idSite'] = 9;
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
  $pageUrlsByDate = $bulkResults[0];
  $pageUrlsData = [];
  foreach ($pageUrlsByDate as $date => $pageUrlsOnDate) {
    foreach ($pageUrlsOnDate as $key => $pageUrl) {
      $pageUrlsData[$date][$key] = [
        'segment' => $pageUrl->segment,
        'nb_hits' => $pageUrl->nb_hits,
        'sum_time_spent' => $pageUrl->sum_time_spent,
      ];
      if (property_exists($pageUrl, 'nb_uniq_visitors')) {
        $pageUrlsData[$date][$key]['nb_uniq_visitors'] = $pageUrl->nb_uniq_visitors;
      }
      if (property_exists($pageUrl, 'exit_nb_visits')) {
        $pageUrlsData[$date][$key]['exit_nb_visits'] = $pageUrl->exit_nb_visits;
      }
    }
  }

  $serialized = serialize($pageUrlsData);
  print $serialized;
}
