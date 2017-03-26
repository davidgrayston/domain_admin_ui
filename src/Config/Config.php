<?php

namespace Drupal\domain_admin_ui\Config;

use Drupal\Core\Config\Config as CoreConfig;
use Drupal\domain\DomainNegotiatorInterface;

/**
 * Extend core Config class to save domain specific configuration.
 */
class Config extends CoreConfig {
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
  public function setDomainNegotiator(DomainNegotiatorInterface $domain_negotiator) {
    $this->domainNegotiator = $domain_negotiator;
  }

  /**
   * {@inheritdoc}
   */
  public function save($has_trusted_data = FALSE) {
    // Remember original config name.
    $originalName = $this->name;

    try {
      // Get domain config name for saving.
      $domainConfigName = $this->getDomainConfigName();

      // If config is new and we are currently saving domain specific configuration,
      // save with original name first so that there is always a default configuration.
      if ($this->isNew && $domainConfigName != $originalName) {
        parent::save($has_trusted_data);
      }

      // Switch to use domain config name and save.
      $this->name = $domainConfigName;
      parent::save($has_trusted_data);
    }
    catch (\Exception $e) {
      // Reset back to original config name if save fails and re-throw.
      $this->name = $originalName;
      throw $e;
    }

    // Reset back to original config name after saving.
    $this->name = $originalName;

    return $this;
  }

  /**
   * Get the domain config name.
   */
  protected function getDomainConfigName() {
    // Return selected config name.
    $domain = $this->domainNegotiator->getActiveDomain(FALSE);
    $overrider = $this->domainNegotiator->getDomainConfigOverrider();
    $configNames = $overrider->getDomainConfigName($this->name, $domain);
    $language_id = $this->domainNegotiator->getSelectedLanguageId();
    $domain_id = $this->domainNegotiator->getSelectedDomainId();

    // Use default config name if domain hasn't been selected.
    if (empty($domain_id)) {
      return $this->name;
    }

    // Use domain config name if language hasn't been selected.
    if (empty($language_id) || $language_id == 'und') {
      return $configNames['domain'];
    }

    // Return language config name if language has been selected.
    return $configNames['langcode'];
  }

}
