/**
 * @file
 * Consent management platform behaviors.
 */

(function (Drupal, $, once) {

  'use strict';

  Drupal.behaviors.burdaCmpSourcepointOpenPrivacyManager = {
    attach: function (context, settings) {
      __tcfapi('addEventListener', 2, function (tcData, success) {
        if (success && tcData.eventStatus === 'tcloaded') {
          $('[data-burda-cmp-open-privacy-manager]', context).once('burdaCmpSourcepointOpenPrivacyManager').each(function () {
            $(this).on('click.burdaCmpSourcepointOpenPrivacyManager', function (e) {
              e.preventDefault();
              e.stopPropagation();
              // Open privacy manager.
              window._sp_.gdpr.loadPrivacyManagerModal(883404); // @TODO PM ID == Correct Privacy Manager Id?
            });
          });
        }
      }, 'cmpReady');
    },
  };

})(Drupal, jQuery, once);
