/**
 * @file
 * Consent management platform behaviors.
 */

(function (Drupal, $, once, drupalSettings) {

  'use strict';

  Drupal.behaviors.burdaCmpSourcepointOpenPrivacyManager = {
    attach: function (context, settings) {
      $('[data-burda-cmp-open-privacy-manager]', context).once('burdaCmpSourcepointOpenPrivacyManager').each(function () {
        $(this).on('click.burdaCmpSourcepointOpenPrivacyManager', function (e) {
          e.preventDefault();
          e.stopPropagation();
          // Open privacy manager.
          window._sp_.gdpr.loadPrivacyManagerModal(drupalSettings.burdaCmp.privacyManagerId);
        });
      });
    },
  };

})(Drupal, jQuery, once, drupalSettings);
