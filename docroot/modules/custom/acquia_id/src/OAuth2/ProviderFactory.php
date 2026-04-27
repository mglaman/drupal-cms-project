<?php

declare(strict_types=1);

namespace Drupal\acquia_id\OAuth2;

use Drupal\Core\Http\ClientFactory;
use Drupal\Core\Url;
use Drupal\acquia_id\AcquiaEnvironmentUrls;
use Drupal\acquia_id\OAuth2\Provider\AcquiaIdProvider;

class ProviderFactory {

  public function __construct(
    private readonly ClientFactory $httpClientFactory,
    private readonly string $clientId,
  ) {
  }

  public function get(): AcquiaIdProvider {
    $provider = new AcquiaIdProvider([
      'clientId' => $this->clientId,
      'redirectUri' => $this->getRedirectUri(),
    ]);
    $provider
      ->setIdpBaseUri(AcquiaEnvironmentUrls::idpBaseUri())
      ->setCloudApiBaseUri(AcquiaEnvironmentUrls::cloudApiBaseUri())
      ->setHttpClient($this->httpClientFactory->fromOptions());
    return $provider;
  }

  private function getRedirectUri(): string {
    return Url::fromRoute('acquia_id.sso')
      ->setAbsolute()
      ->toString(TRUE)
      ->getGeneratedUrl();
  }

}
