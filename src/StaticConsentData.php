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
          'id' => 10007,
          'label' => $this->t('Facebook', [], ['context' => 'Cookie consent provider']),
          // @todo Toggle label should be translatable for Facebook posts.
          'toggle_label' => 'Facebook-Posts anzeigen',
          'purposes' => [
            29,
          ],
        ],

        // Google Maps.
        static::VENDOR_GOOGLE_MAPS => [
          'id' => 10219,
          'label' => $this->t('Google Maps', [], ['context' => 'Cookie consent provider']),
          // @todo Toggle label should be translatable for Google Maps.
          'toggle_label' => 'Google Maps anzeigen',
          'purposes' => [
            29,
          ],
        ],

        // Instagram.
        static::VENDOR_INSTAGRAM => [
          'id' => 10019,
          'label' => $this->t('Instagram', [], ['context' => 'Cookie consent provider']),
          // @todo Toggle label should be translatable for Instagram posts.
          'toggle_label' => 'Instagram-Posts anzeigen',
          'purposes' => [
            29,
          ],
        ],

        // Pinterest.
        static::VENDOR_PINTEREST => [
          'id' => 10031,
          'label' => $this->t('Pinterest', [], ['context' => 'Cookie consent provider']),
          // @todo Toggle label should be translatable for Pinterest posts.
          'toggle_label' => 'Pinterest-Posts anzeigen',
          'purposes' => [
            29,
          ],
        ],

        // Retyp LLC (OptinMonster).
        static::VENDOR_RETYP_LLC => [
          'id' => 10195,
          'label' => $this->t('Retyp LLC', [], ['context' => 'Cookie consent provider']),
          // @todo Toggle label for Retyp LLC / OptinMonster (if needed).
          'toggle_label' => NULL,
          'purposes' => [
            26,
          ],
        ],

        // Riddle Technologies AG.
        static::VENDOR_RIDDLE => [
          'id' => 10196,
          'label' => $this->t('Riddle Technologies AG', [], ['context' => 'Cookie consent provider']),
          // @todo Toggle label should be translatable for Riddle polls.
          'toggle_label' => 'Riddle-Umfragen anzeigen',
          'purposes' => [
            26,
            27,
          ],
        ],

        // TargetVideo GmbH.
        static::VENDOR_TARGETVIDEO_GMBH => [
          'id' => 10200,
          'label' => $this->t('TargetVideo GmbH', [], ['context' => 'Cookie consent provider']),
          // @todo Toggle label for TargetVideo GmbH (if needed).
          'toggle_label' => NULL,
          'purposes' => [
            28,
          ],
        ],

        // Twitter.
        static::VENDOR_TWITTER => [
          'id' => 10006,
          'label' => $this->t('Twitter', [], ['context' => 'Cookie consent provider']),
          // @todo Toggle label should be translatable for Twitter posts.
          'toggle_label' => 'Twitter-Posts anzeigen',
          'purposes' => [
            29,
          ],
        ],

        // Vimeo.
        static::VENDOR_VIMEO => [
          'id' => 10021,
          'label' => $this->t('Vimeo', [], ['context' => 'Cookie consent provider']),
          // @todo Toggle label should be translatable for Vimeo videos.
          'toggle_label' => 'Vimeo-Videos anzeigen',
          'purposes' => [
            26,
            29,
          ],
        ],

        // Xandr, Inc.
        static::VENDOR_XANDR => [
          'id' => 32,
          'label' => $this->t('Xandr, Inc.', [], ['context' => 'Cookie consent provider']),
          // @todo Toggle label for Xandr, Inc. (if needed).
          'toggle_label' => NULL,
          // @todo Still needs correct purpose IDs for Xandr, Inc.
          'purposes' => [],
        ],

        // YouTube.
        static::VENDOR_YOUTUBE => [
          'id' => 10020,
          'label' => $this->t('YouTube', [], ['context' => 'Cookie consent provider']),
          // @todo Toggle label should be translatable for YouTube videos.
          'toggle_label' => 'YouTube-Videos anzeigen',
          'purposes' => [
            26,
            29,
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

    if (is_numeric($vendor)) {
      $vendor = $this->getVendorName($vendor);
    }

    return isset($data[$vendor]['toggle_label']) ? $data[$vendor]['toggle_label'] : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getVendorId($vendor) {
    $data = $this->getAll();

    return isset($data[$vendor]['id']) ? $data[$vendor]['id'] : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getVendorLabel($vendor) {
    $data = $this->getAll();

    if (is_numeric($vendor)) {
      $vendor = $this->getVendorName($vendor);
    }

    return isset($data[$vendor]['label']) ? $data[$vendor]['label'] : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getVendorName($vendor) {
    $data = $this->getAll();

    if (($key = array_search($vendor, array_column($data, 'id'))) !== FALSE) {
      return array_keys($data)[$key];
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getPurposeIds($vendor) {
    $data = $this->getAll();

    if (is_numeric($vendor)) {
      $vendor = $this->getVendorName($vendor);
    }

    $purposes = isset($data[$vendor]['purposes']) ? $data[$vendor]['purposes'] : [];

    // Ensure legitimate interest purpose.
    $purposes[] = 1;

    // Filter duplicates.
    $purposes = array_unique($purposes);

    // Sort purposes.
    sort($purposes);

    return $purposes;
  }

}
