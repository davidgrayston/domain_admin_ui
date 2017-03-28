<?php

namespace Drupal\domain_admin_ui;

use Drupal\domain\DomainNegotiator;
use Drupal\domain_config\DomainConfigOverrider;

/**
 * {@inheritdoc}
 */
class DomainAdminUINegotiator extends DomainNegotiator {
  /**
   * @var DomainConfigOverrider
   */
  protected $domainConfigOverrider;

  /**
   * Set the domain config overrider.
   *
   * @param DomainConfigOverrider $domain_config_overrider
   */
  public function setDomainConfigOverrider(DomainConfigOverrider $domain_config_overrider) {
    $this->domainConfigOverrider = $domain_config_overrider;
  }

  /**
   * Get the domain config overrider.
   *
   * @return DomainConfigOverrider
   */
  public function getDomainConfigOverrider() {
    return $this->domainConfigOverrider;
  }

  /**
   * Determine the active domain.
   */
  protected function negotiateActiveDomain() {
    // Set http host to be that of the selected domain to configure.
    if ($selected_domain = $this->getSelectedDomain()) {
      $httpHost = $selected_domain->getHostname();
    }
    else {
      $httpHost = $this->negotiateActiveHostname();
    }
    $this->setRequestDomain($httpHost);
    return $this->domain;
  }

  /**
   * Set selected language.
   */
  public function initSelectedLanguage() {
    if ($selected_language = $this->getSelectedLanguage()) {
      $this->domainConfigOverrider->setLanguage($selected_language);
    }
  }

  /**
   * Get the selected domain.
   */
  public function getSelectedDomain() {
    $selected_domain_id = $this->getSelectedDomainId();
    if ($selected_domain_id && $selected_domain = $this->domainLoader->load($selected_domain_id)) {
      return $selected_domain;
    }
  }

  /**
   * Get the selected domain ID.
   */
  public function getSelectedDomainId() {
    // Return selected domain ID on admin paths only.
    return !empty($_SESSION['domain_admin_ui']['selected_domain']) ? $_SESSION['domain_admin_ui']['selected_domain'] : '';
  }

  /**
   * Set the current selected domain ID.
   *
   * @param string $domain_id
   */
  public function setSelectedDomain($domain_id) {
    if ($domain = $this->domainLoader->load($domain_id)) {
      // Set session for subsequent request.
      $_SESSION['domain_admin_ui']['selected_domain'] = $domain_id;
      // Switch active domain now so that selected domain configuration can be loaded immediatly.
      // This is primarily for switching domain with AJAX request.
      $this->domainConfigOverrider->setDomain($domain);
    }
    else {
      $_SESSION['domain_admin_ui']['selected_domain'] = '';
      parent::negotiateActiveDomain();
    }
  }

  /**
   * Set the selected language.
   * @param string $language_id
   */
  public function setSelectedLanguage($language_id) {
    if ($language = \Drupal::languageManager()->getLanguage($language_id)) {
      // Set session for subsequent request.
      $_SESSION['domain_admin_ui']['selected_language'] = $language_id;
      // Switch active language now so that selected domain configuration can be loaded immediatly.
      // This is primarily for switching domain with AJAX request.
      $this->domainConfigOverrider->setLanguage($language);
    }
  }

  /**
   * Get the selected language ID.
   */
  public function getSelectedLanguageId() {
    return !empty($_SESSION['domain_admin_ui']['selected_language']) ? $_SESSION['domain_admin_ui']['selected_language'] : '';
  }

  /**
   * Get the selected language.
   */
  public function getSelectedLanguage() {
    $selected_language_id = $this->getSelectedLanguageId();
    if ($selected_language_id && $selected_language = \Drupal::languageManager()->getLanguage($selected_language_id)) {
      return $selected_language;
    }
  }
}
