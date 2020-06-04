<?php

/**
 * @file
 * Preprocessors and helper functions to make theming easier.
 */

/**
 * Implements hook_preprocess_HOOK() for burda-cmp-conditional-content.html.twig.
 */
function template_preprocess_burda_cmp_conditional_content(&$variables) {
  // Preprocess vendor value.
  $variables['vendor'] = !empty($variables['vendor']) && is_numeric($variables['vendor']) ? (int) $variables['vendor'] : NULL;

  // Preprocess purpose values.
  if (empty($variables['purposes'])) {
    $variables['purpose'] = [];
  }
  elseif (is_array($variables['purposes'])) {
    $variables['purposes'] = array_filter($variables['purposes'], function ($item) {
      return is_numeric($item);
    });
  }

  $variables['purposes'] = array_map(function ($item) {
    return trim($item);
  }, $variables['purposes']);
}
