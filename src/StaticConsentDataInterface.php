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
   * Vendor name: Retyp LLC (OptinMonster).
   */
  const VENDOR_RETYP_LLC = 'retyp_llc';

  /**
   * Vendor name: Riddle.
   */
  const VENDOR_RIDDLE = 'riddle';

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
   * Return all static cookie consent data.
   *
   * @return array
   *   A keyed array of all static cookie consent data. The key is the vendor
   *   machine name as  defined by the StaticConsentDataInterface::VENDOR_*
   *   constants, the value is a keyed array with the following items:
   *     - id: The vendor ID.
   *     - purposes: An array containing the IDs of all required purposes.
   *     - label: The human-readable label for the vendor.
   */
  public function getAll();

  /**
   * Return required purpose IDs for given vendor.
   *
   * @param string|int $vendor
   *   Either a vendor ID or a vendor name as defined by the
   *   StaticConsentDataInterface::VENDOR_* constants.
   *
   * @return int[]
   *   An array of purpose IDs on success, otherwise an empty array.
   */
  public function getPurposeIds($vendor);

  /**
   * Return toggle button label for given vendor.
   *
   * @param string|int $vendor
   *   Either a vendor ID or a vendor name as defined by the
   *   StaticConsentDataInterface::VENDOR_* constants.
   *
   * @return string[]
   *   The toggle button label on success, otherwise NULL.
   */
  public function getToggleLabel($vendor);

  /**
   * Return vendor ID for given vendor name.
   *
   * @param string $vendor
   *   A vendor name as defined by the
   *   StaticConsentDataInterface::VENDOR_* constants.
   *
   * @return int|null
   *   The vendor ID on success, otherwise NULL.
   */
  public function getVendorId($vendor);

  /**
   * Return human-readable label for given vendor.
   *
   * @param string|int $vendor
   *   Either a vendor ID or a vendor name as defined by the
   *   StaticConsentDataInterface::VENDOR_* constants.
   *
   * @return string[]
   *   The human-readable label on success, otherwise NULL.
   */
  public function getVendorLabel($vendor);

  /**
   * Return vendor name for given vendor ID.
   *
   * @param int $vendor
   *   A vendor ID.
   *
   * @return string|null
   *   The vendor name as defined by the StaticConsentDataInterface::VENDOR_*
   *   constants on success, otherwise NULL.
   */
  public function getVendorName($vendor);

}
