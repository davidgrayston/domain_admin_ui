<?php

namespace Drupal\domain_admin_ui\Cache\Context;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\Context\CalculatedCacheContextInterface;
use Drupal\domain\DomainNegotiatorInterface;

/**
 * Selected domain context
 */
class SelectedDomain implements CalculatedCacheContextInterface {
  /**
   * The Domain negotiator.
   *
   * @var DomainNegotiatorInterface
   */
  protected $domainNegotiator;

  /**
   * Set the Domain negotiator.
   *
   * @param DomainNegotiatorInterface $domain_negotiator
   */
  public function __construct(DomainNegotiatorInterface $domain_negotiator) {
    $this->domainNegotiator = $domain_negotiator;
  }

  /**
   * {@inheritdoc}
   */
  public static function getLabel() {
    return t('Selected Domain');
  }

  /**
   * {@inheritdoc}
   */
  public function getContext($query_arg = NULL) {
    return $this->domainNegotiator->getSelectedDomainId() . $this->domainNegotiator->getSelectedLanguageId();
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata($query_arg = NULL) {
    return new CacheableMetadata();
  }

}
