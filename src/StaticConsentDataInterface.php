<?php

namespace Drupal\burda_cmp;

/**
 * Interface for static cookie consent data services.
 */
interface StaticConsentDataInterface {

  /**
   * Vendor name: Facebook.
   */
  const VENDOR_FACEBOOK = 'facebook';

  /**
   * Vendor name: Google Maps.
   */
  const VENDOR_GOOGLE_MAPS = 'google_maps';

  /**
   * Vendor name: Instagram.
   */
  const VENDOR_INSTAGRAM = 'instagram';

  /**
   * Vendor name: Pinterest.
   */
  const VENDOR_PINTEREST = 'pinterest';

  /**
   * Vendor name: TargetVideo GmbH.
   */
  const VENDOR_TARGETVIDEO_GMBH = 'targetvideo_gmbh';

  /**
   * Vendor name: Twitter.
   */
  const VENDOR_TWITTER = 'twitter';

  /**
   * Vendor name: Vimeo.
   */
  const VENDOR_VIMEO = 'vimeo';

  /**
   * Vendor name: Xandr, Inc.
   */
  const VENDOR_XANDR = 'xandr';

  /**
   * Vendor name: YouTube.
   */
  const VENDOR_YOUTUBE = 'youtube';

  /**
   * Purpose Name: Store and/or access information on a device.
   */
  const PURPOSE_STORE_AND_OR_ACCESS_INFORMATION_ON_A_DEVICE = '6489861f44cf6406ddda4023';

  /**
   * Purpose Name: Use limited data to select advertising.
   */
  const PURPOSE_USE_LIMITED_DATA_TO_SELECT_ADVERTISING = '6489861e44cf6406ddda28e9';

  /**
   * Purpose Name: Create profiles for personalised advertising.
   */
  const PURPOSE_CREATE_PROFILES_FOR_PERSONALISED_ADVERTISING = '6489861e44cf6406ddda2c78';

  /**
   * Purpose Name: Use profiles to select personalised advertising.
   */
  const PURPOSE_USE_PROFILES_TO_SELECT_PERSONALISED_ADVERTISING = '6489861e44cf6406ddda2fa3';

  /**
   * Purpose Name: Create profiles to personalise content.
   */
  const PURPOSE_CREATE_PROFILES_TO_PERSONALISE_CONTENT = '6489861e44cf6406ddda32bb';

  /**
   * Purpose Name: Use profiles to select personalised content.
   */
  const PURPOSE_USE_PROFILES_TO_SELECT_PERSONALISED_CONTENT = '6489861e44cf6406ddda33c5';

  /**
   * Purpose Name: Measure advertising performance.
   */
  const PURPOSE_MEASURE_ADVERTISING_PERFORMANCE = '6489861e44cf6406ddda34a9';

  /**
   * Purpose Name: Measure content performance.
   */
  const PURPOSE_MEASURE_CONTENT_PERFORMANCE = '6489861f44cf6406ddda38dc';

  /**
   * Purpose Name: Understand audiences through statistics or combinations ...
   */
  const PURPOSE_UNDERSTAND_AUDIENCES_THROUGH_STATISTICS = '6489861f44cf6406ddda3a8c';

  /**
   * Develop and improve services.
   */
  const PURPOSE_DEVELOP_AND_IMPROVE_SERVICES = '6489861f44cf6406ddda3cba';

  /**
   * Use limited data to select content.
   */
  const PURPOSE_USE_LIMITED_DATA_TO_SELECT_CONTENT = '652692cc25bbd005067b4994';

  /**
   * Unbedingt erforderliche Cookies.
   */
  const PURPOSE_ABSOLUTELY_REQUIRED_COOKIES = '6489861f44cf6406ddda4011';

  /**
   * Funktional.
   */
  const PURPOSE_FUNCTIONAL = '6489861f44cf6406ddda4016';

  /**
   * Analytik.
   */
  const PURPOSE_ANALYTIC = '6489861f44cf6406ddda401a';

  /**
   * Werbung (Nicht-IAB Anbieter).
   */
  const PURPOSE_ADS_NON_IAB = '6489861f44cf6406ddda401d';

  /**
   * Soziale Medien.
   */
  const PURPOSE_SOCIAL_MEDIA = '6489861f44cf6406ddda401f';

  /**
   * Direktes Marketing.
   */
  const PURPOSE_DIRECT_MARKETING = '6493f84f36160804ecc46e5f';

  /**
   * Datenaustausch.
   */
  const PURPOSE_DATA_EXCHANGE = '6493f84f36160804ecc46e64';

  /**
   * Return all static cookie consent data.
   *
   * @return array
   *   A keyed array of all static cookie consent data. The key is the vendor
   *   machine name as defined by the StaticConsentDataInterface::VENDOR_*
   *   constants, the value is a keyed array with the following items:
   *     - id: The vendor ID.
   *     - purposes: An array containing the IDs of all required purposes.
   *     - label: The human-readable label for the vendor.
   *
   * @see \Drupal\burda_cmp\StaticConsentDataInterface
   */
  public function getAll();

  /**
   * Return all vendor IDs from static cookie consent data.
   *
   * @return string[]
   *   An indexed array containing all vendor IDs from getAll() function as
   *   values.
   */
  public function getAllVendorIds(): array;

  /**
   * Return required purpose IDs for given vendor.
   *
   * @param  string  $vendor
   *   Either a vendor ID or a vendor name as defined by the
   *   StaticConsentDataInterface::VENDOR_* constants.
   *
   * @return string[]
   *   An array of purpose IDs on success, otherwise an empty array.
   *
   * @see \Drupal\burda_cmp\StaticConsentDataInterface
   */
  public function getPurposeIds($vendor);

  /**
   * Return toggle button label for given vendor.
   *
   * @param  string  $vendor
   *   Either a vendor ID or a vendor name as defined by the
   *   StaticConsentDataInterface::VENDOR_* constants.
   *
   * @return string[]
   *   The toggle button label on success, otherwise NULL.
   *
   * @see \Drupal\burda_cmp\StaticConsentDataInterface
   */
  public function getToggleLabel($vendor);

  /**
   * Return vendor ID for given vendor name.
   *
   * @param  string  $vendorName
   *   A vendor name as defined by the
   *   StaticConsentDataInterface::VENDOR_* constants.
   *
   * @return string|null
   *   The vendor ID on success, otherwise NULL.
   *
   * @see \Drupal\burda_cmp\StaticConsentDataInterface
   */
  public function getVendorId($vendorName);

  /**
   * Return human-readable label for given vendor.
   *
   * @param  string  $vendor
   *   Either a vendor ID or a vendor name as defined by the
   *   StaticConsentDataInterface::VENDOR_* constants.
   *
   * @return string[]
   *   The human-readable label on success, otherwise NULL.
   *
   * @see \Drupal\burda_cmp\StaticConsentDataInterface
   */
  public function getVendorLabel($vendor);

  /**
   * Return vendor name for given vendor ID.
   *
   * @param  string  $vendorId
   *   A vendor ID.
   *
   * @return string|null
   *   The vendor name as defined by the StaticConsentDataInterface::VENDOR_*
   *   constants on success, otherwise NULL.
   *
   * @see \Drupal\burda_cmp\StaticConsentDataInterface
   */
  public function getVendorName($vendorId);

  /**
   * Checks if the param is a vendor ID from the static cookie consent data.
   *
   * @param  string  $vendor
   *   A vendor name or a vendor ID.
   *
   * @return bool
   *   TRUE, if the vendor value is found as an ID value in the static cookie
   *   consent data, otherwise FALSE.
   */
  public function isVendorId(string $vendor): bool;

}
