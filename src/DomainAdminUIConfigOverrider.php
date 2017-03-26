<?php

namespace Drupal\domain_admin_ui;

use Drupal\domain_config\DomainConfigOverrider;
use Drupal\domain\DomainInterface;
use Drupal\Core\Language\LanguageInterface;

/**
 * Extend DomainConfigOverrider to allow domain to be set.
 */
class DomainAdminUIConfigOverrider extends DomainConfigOverrider {
  /**
   * Set the domain.
   *
   * @param DomainInterface $domain
   */
  public function setDomain(DomainInterface $domain) {
    $this->domain = $domain;
  }
  
  /**
   * Set the language.
   *
   * @param LanguageInterface $language
   */
  public function setLanguage(LanguageInterface $language) {
    $this->language = $language;
  }
}
