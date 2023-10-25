<?php

namespace Drupal\burda_cmp;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Provides a service for static cookie consent data.
 */
class StaticConsentData implements StaticConsentDataInterface {

  use StringTranslationTrait;

  /**
   * The static cookie consent data.
   *
   * @var array
   */
  protected $data;

  /**
   * {@inheritdoc}
   */
  public function getAll() {
    if (!isset($this->data)) {
      $this->data = [
        // Facebook.
        static::VENDOR_FACEBOOK => [
          'id' => '5e716fc09a0b5040d575080f',
          'label' => $this->t('Facebook', [], ['context' => 'Cookie consent provider']),
          // @todo Toggle label should be translatable for Facebook posts.
          'toggle_label' => 'Facebook-Posts anzeigen',
          'purposes' => [
            static::PURPOSE_STORE_AND_OR_ACCESS_INFORMATION_ON_A_DEVICE,
            static::PURPOSE_FUNCTIONAL,
            static::PURPOSE_SOCIAL_MEDIA,
          ],
        ],

        // Google Maps.
        static::VENDOR_GOOGLE_MAPS => [
          'id' => '5eb97b265852312e6a9fbf31',
          'label' => $this->t('Google Maps', [], ['context' => 'Cookie consent provider']),
          // @todo Toggle label should be translatable for Google Maps.
          'toggle_label' => 'Google Maps anzeigen',
          'purposes' => [
            static::PURPOSE_STORE_AND_OR_ACCESS_INFORMATION_ON_A_DEVICE,
            static::PURPOSE_SOCIAL_MEDIA,
          ],
        ],

        // Instagram.
        static::VENDOR_INSTAGRAM => [
          'id' => '6054c53ca228639c6f285121',
          'label' => $this->t('Instagram', [], ['context' => 'Cookie consent provider']),
          // @todo Toggle label should be translatable for Instagram posts.
          'toggle_label' => 'Instagram-Posts anzeigen',
          'purposes' => [
            static::PURPOSE_STORE_AND_OR_ACCESS_INFORMATION_ON_A_DEVICE,
            static::PURPOSE_FUNCTIONAL,
            static::PURPOSE_SOCIAL_MEDIA,
          ],
        ],

        // Pinterest.
        static::VENDOR_PINTEREST => [
          'id' => '5e839a38b8e05c4e491e738e',
          'label' => $this->t('Pinterest', [], ['context' => 'Cookie consent provider']),
          // @todo Toggle label should be translatable for Pinterest posts.
          'toggle_label' => 'Pinterest-Posts anzeigen',
          'purposes' => [
            static::PURPOSE_STORE_AND_OR_ACCESS_INFORMATION_ON_A_DEVICE,
            static::PURPOSE_FUNCTIONAL,
            static::PURPOSE_SOCIAL_MEDIA,
          ],
        ],

//        // Retyp LLC (OptinMonster).
//        static::VENDOR_RETYP_LLC => [
//          // @TODO: Missing in Sourcepoint..
//          'id' => 10195,
//          'label' => $this->t('Retyp LLC', [], ['context' => 'Cookie consent provider']),
//          // @todo Toggle label for Retyp LLC / OptinMonster (if needed).
//          'toggle_label' => NULL,
//          'purposes' => [],
//        ],
//
//        // Riddle Technologies AG.
//        static::VENDOR_RIDDLE => [
//          // @TODO: Missing in Sourcepoint..
//          'id' => 10196,
//          'label' => $this->t('Riddle Technologies AG', [], ['context' => 'Cookie consent provider']),
//          // @todo Toggle label should be translatable for Riddle polls.
//          'toggle_label' => 'Riddle-Umfragen anzeigen',
//          'purposes' => [],
//        ],

        // TargetVideo GmbH.
        static::VENDOR_TARGETVIDEO_GMBH => [
          'id' => '5f0838a5b8e05c065164a384',
          'label' => $this->t('TargetVideo GmbH', [], ['context' => 'Cookie consent provider']),
          // @todo Toggle label for TargetVideo GmbH (if needed).
          'toggle_label' => NULL,
          'purposes' => [
            static::PURPOSE_MEASURE_CONTENT_PERFORMANCE
          ],
        ],

        // Twitter. / X Corp.
        static::VENDOR_TWITTER => [
          'id' => '5e71760b69966540e4554f01',
          'label' => $this->t('Twitter', [], ['context' => 'Cookie consent provider']),
          // @todo Toggle label should be translatable for Twitter posts.
          'toggle_label' => 'Twitter-Posts anzeigen',
          'purposes' => [
            static::PURPOSE_STORE_AND_OR_ACCESS_INFORMATION_ON_A_DEVICE,
            static::PURPOSE_FUNCTIONAL,
            static::PURPOSE_SOCIAL_MEDIA,
          ],
        ],

        // Vimeo.
        static::VENDOR_VIMEO => [
          'id' => '5eac148d4bfee33e7280d13b',
          'label' => $this->t('Vimeo', [], ['context' => 'Cookie consent provider']),
          // @todo Toggle label should be translatable for Vimeo videos.
          'toggle_label' => 'Vimeo-Videos anzeigen',
          'purposes' => [
            static::PURPOSE_STORE_AND_OR_ACCESS_INFORMATION_ON_A_DEVICE,
            static::PURPOSE_FUNCTIONAL,
            static::PURPOSE_SOCIAL_MEDIA,
          ],
        ],

        // Xandr, Inc.
        static::VENDOR_XANDR => [
          'id' => '5e7ced57b8e05c4854221bba',
          'label' => $this->t('Xandr, Inc.', [], ['context' => 'Cookie consent provider']),
          // @todo Toggle label for Xandr, Inc. (if needed).
          'toggle_label' => NULL,
          // @todo Still needs correct purpose IDs for Xandr, Inc.
          'purposes' => [
            static::PURPOSE_STORE_AND_OR_ACCESS_INFORMATION_ON_A_DEVICE,
            static::PURPOSE_USE_LIMITED_DATA_TO_SELECT_ADVERTISING,
            static::PURPOSE_CREATE_PROFILES_FOR_PERSONALISED_ADVERTISING,
            static::PURPOSE_USE_PROFILES_TO_SELECT_PERSONALISED_ADVERTISING,
            static::PURPOSE_MEASURE_ADVERTISING_PERFORMANCE,
            static::PURPOSE_DEVELOP_AND_IMPROVE_SERVICES,
          ],
        ],

        // YouTube.
        static::VENDOR_YOUTUBE => [
          'id' => '5e7ac3fae30e7d1bc1ebf5e8',
          'label' => $this->t('YouTube', [], ['context' => 'Cookie consent provider']),
          // @todo Toggle label should be translatable for YouTube videos.
          'toggle_label' => 'YouTube-Videos anzeigen',
          'purposes' => [
            static::PURPOSE_STORE_AND_OR_ACCESS_INFORMATION_ON_A_DEVICE,
            static::PURPOSE_FUNCTIONAL,
            static::PURPOSE_SOCIAL_MEDIA,
          ],
        ],
      ];
    }

    return $this->data;
  }

  /**
   * {@inheritdoc}
   */
  public function getToggleLabel($vendor) {
    $data = $this->getAll();

    if ($this->isVendorId($vendor)) {
      $vendor = $this->getVendorName($vendor);
    }

    return $data[$vendor]['toggle_label'] ?? NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getVendorId($vendorName) {
    $data = $this->getAll();

    return $data[$vendorName]['id'] ?? NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getVendorLabel($vendor) {
    $data = $this->getAll();

    if ($this->isVendorId($vendor)) {
      $vendor = $this->getVendorName($vendor);
    }

    return $data[$vendor]['label'] ?? NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getVendorName($vendorId) {
    $data = $this->getAll();

    if (($key = array_search($vendorId, array_column($data, 'id'))) !== FALSE) {
      return array_keys($data)[$key];
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getPurposeIds($vendor): array {
    $data = $this->getAll();

    if ($this->isVendorId($vendor)) {
      $vendor = $this->getVendorName($vendor);
    }

    $purposes = $data[$vendor]['purposes'] ?? [];

    // Ensure legitimate interest purpose.
    $purposes[] = 1;

    // Filter duplicates.
    $purposes = array_unique($purposes);

    // Sort purposes.
    sort($purposes);

    return $purposes;
  }

  /**
   * {@inheritdoc}
   */
  public function getAllVendorIds(): array {
    $data = $this->getAll();
    return array_column($data, 'id');
  }

  /**
   * {@inheritdoc}
   */
  public function isVendorId(string $vendor): bool {
    $vendorIds = $this->getAllVendorIds();
    return in_array($vendor, $vendorIds);
  }

}
