<?php

/**
 * @file
 * Preprocessors and helper functions to make theming easier.
 */

/**
 * Implements hook_preprocess_HOOK() for burda-cmp-conditional-content.html.twig.
 */
function template_preprocess_burda_cmp_conditional_content(&$variables) {
  /** @var \Drupal\burda_cmp\StaticConsentDataInterface $static_consent_data */
  $static_consent_data = \Drupal::service('burda_cmp.static_consent_data');

  // Preprocess vendor value.
  if (!empty($variables['vendor'])) {
    if (is_numeric($variables['vendor'])) {
      $variables['vendor'] = (int) $variables['vendor'];
    }

    // Determine vendor ID by vendor machine name.
    else {
      $variables['vendor'] = $static_consent_data->getVendorId($variables['vendor']);
    }
  }

  // Preprocess human-readable vendor label.
  if (empty($variables['vendor_label']) && !empty($variables['vendor'])) {
    $variables['vendor_label'] = $static_consent_data->getVendorLabel($variables['vendor']);
  }

  // Preprocess toggle label.
  if (empty($variables['toggle_label']) && !empty($variables['vendor'])) {
    $variables['toggle_label'] = $static_consent_data->getToggleLabel($variables['vendor']);
  }

  // Preprocess purpose values.
  if (empty($variables['purposes'])) {
    $variables['purposes'] = [];

    // Determine purposes by vendor ID.
    if (!empty($variables['vendor'])) {
      $variables['purposes'] = $static_consent_data->getPurposeIds($variables['vendor']);
    }
  }
  elseif (is_array($variables['purposes'])) {
    $variables['purposes'] = array_filter($variables['purposes'], function ($item) {
      return is_numeric($item);
    });
  }

  // Trim purpose IDs.
  $variables['purposes'] = array_map(function ($item) {
    return trim($item);
  }, $variables['purposes']);

  // Sort purpose IDs.
  sort($variables['purposes']);
}
