<?php

declare(strict_types=1);

namespace Drupal\acquia_id;

final class AcquiaEnvironmentUrls {

  private const PROD_ENVIRONMENT = 'prod';
  private const PROD_IDP_BASE_URI = 'https://id.acquia.com/oauth2/default';
  private const PROD_CLOUD_API_BASE_URI = 'https://cloud.acquia.com';
  private const STAGING_IDP_BASE_URI = 'https://staging.id.acquia.com/oauth2/default';
  private const STAGING_CLOUD_API_BASE_URI = 'https://staging.cloud.acquia.com';

  public static function idpBaseUri(?string $environment = NULL): string {
    return self::isProduction($environment) ? self::PROD_IDP_BASE_URI : self::STAGING_IDP_BASE_URI;
  }

  public static function cloudApiBaseUri(?string $environment = NULL): string {
    return self::isProduction($environment) ? self::PROD_CLOUD_API_BASE_URI : self::STAGING_CLOUD_API_BASE_URI;
  }

  public static function logoutRedirectUri(?string $environment = NULL): string {
    return self::cloudApiBaseUri($environment);
  }

  public static function idpHost(?string $environment = NULL): string {
    return (string) parse_url(self::idpBaseUri($environment), PHP_URL_HOST);
  }

  public static function cloudApiHost(?string $environment = NULL): string {
    return (string) parse_url(self::cloudApiBaseUri($environment), PHP_URL_HOST);
  }

  private static function isProduction(?string $environment = NULL): bool {
    $resolvedEnvironment = $environment ?? (getenv('AH_SITE_ENVIRONMENT') ?: '');
    return $resolvedEnvironment === self::PROD_ENVIRONMENT;
  }

}
