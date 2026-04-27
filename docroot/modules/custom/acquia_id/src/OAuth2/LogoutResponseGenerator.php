<?php

declare(strict_types=1);

namespace Drupal\acquia_id\OAuth2;

use Drupal\acquia_id\AcquiaEnvironmentUrls;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\Core\Session\AccountInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

final readonly class LogoutResponseGenerator {

  public function __construct(
    private AccessTokenRepository $accessTokenRepository,
    private AccountInterface $account,
  ) {}

  /**
   * Gets the logout redirect response.
   *
   * Redirects through the IdP logout endpoint when a valid token with an
   * id_token is available, so the OKTA session is terminated alongside the
   * Drupal session.
   */
  public function get(): TrustedRedirectResponse {
    $idpBaseUri = AcquiaEnvironmentUrls::idpBaseUri();
    $idpLogoutRedirectUri = AcquiaEnvironmentUrls::logoutRedirectUri();

    try {
      $token = $this->accessTokenRepository->get((int) $this->account->id());
      if ($token) {
        $id_token = $token->getValues()['id_token'] ?? '';
        $logout_url = $idpBaseUri . "/v1/logout?id_token_hint=$id_token&post_logout_redirect_uri=$idpLogoutRedirectUri";
      }
      else {
        $logout_url = $idpLogoutRedirectUri;
      }
    }
    catch (IdentityProviderException) {
      $logout_url = $idpLogoutRedirectUri;
    }

    $response = new TrustedRedirectResponse($logout_url);
    $response
      ->getCacheableMetadata()
      ->setCacheMaxAge(0);
    return $response;
  }

}
