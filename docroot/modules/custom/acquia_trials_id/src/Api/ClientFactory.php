<?php

declare(strict_types=1);

namespace Drupal\acquia_trials_id\Api;

use Drupal\Core\Http\ClientFactory as HttpClientFactory;
use Drupal\acquia_id\AcquiaEnvironmentUrls;

final readonly class ClientFactory {

  public function __construct(
    private HttpClientFactory $httpClientFactory,
  ) {}

  public function get(string $accessToken): Client {
    return new Client($this->httpClientFactory->fromOptions([
      'base_uri' => AcquiaEnvironmentUrls::cloudApiBaseUri(),
      'headers' => [
        'Accept' => 'application/json, version=2',
        'Authorization' => "Bearer $accessToken",
      ],
    ]));
  }

}
