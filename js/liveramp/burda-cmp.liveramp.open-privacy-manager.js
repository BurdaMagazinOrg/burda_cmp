/**
 * Behavior for 'Open privacy manager (LiveRamp)' links.
 */

(function ($, Drupal) {

  'use strict';

  /**
   * Enables the 'Open privacy manager (LiveRamp)' link functionality.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches the 'Open privacy manager (LiveRamp)' behavior.
   */
  Drupal.behaviors.burdaCmpLiveRampOpenPrivacyManager = {
    attach: function attach(context) {
      // Initialize 'Open privacy manager' link when CMP library is ready.
      __tcfapi('addEventListener', null, function () {
        $('[data-burda-cmp-open-privacy-manager]', context).once('burdaCmpLiveRampOpenPrivacyManager').each(function () {
          $(this).on('click.burdaCmpLiveRampOpenPrivacyManager', function (e) {
            e.preventDefault();
            e.stopPropagation();

            // Open privacy manager.
            __tcfapi('showConsentManager', null, function () {
            });
          })
        });
      }, 'cmpReady');
    }
  };

})(jQuery, Drupal);
