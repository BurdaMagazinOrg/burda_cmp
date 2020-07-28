<?php

namespace Drupal\burda_cmp\Template;

use Drupal\burda_cmp\StaticConsentDataInterface;

/**
 * A class providing Drupal Twig extensions for burda_cmp module.
 *
 * @see \Drupal\Core\CoreServiceProvider
 */
class TwigExtension extends \Twig_Extension {

  /**
   * The static cookie consent data service.
   *
   * @var \Drupal\burda_cmp\StaticConsentDataInterface
   */
  protected $staticConsentData;

  /**
   * Constructs a new TwigExtension.
   *
   * @param \Drupal\burda_cmp\StaticConsentDataInterface $static_consent_data
   *   The static cookie consent data service.
   */
  public function __construct(StaticConsentDataInterface $static_consent_data) {
    $this->staticConsentData = $static_consent_data;
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'burda_cmp';
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('burda_cmp_purpose_ids', [$this, 'cmpPurposeIds']),
      new \Twig_SimpleFunction('burda_cmp_vendor_id', [$this, 'cmpVendorId']),
    ];
  }

  /**
   * Return required purpose IDs for given vendor.
   *
   * @param string|int $vendor
   *   Either a vendor ID or a vendor name as defined by the
   *   StaticConsentDataInterface::VENDOR_* constants.
   *
   * @return int[]
   *   An array of purpose IDs on success, otherwise an empty array.
   *
   * @see \Drupal\burda_cmp\StaticConsentDataInterface
   */
  public function cmpPurposeIds($vendor) {
   return $this->staticConsentData->getPurposeIds($vendor);
  }

  /**
   * Return vendor ID for given vendor name.
   *
   * @param string $vendor
   *   A vendor name as defined by the
   *   StaticConsentDataInterface::VENDOR_* constants.
   *
   * @return int|null
   *   The vendor ID on success, otherwise NULL.
   *
   * @see \Drupal\burda_cmp\StaticConsentDataInterface
   */
  public function cmpVendorId($vendor) {
    return $this->staticConsentData->getVendorid($vendor);
  }

}
