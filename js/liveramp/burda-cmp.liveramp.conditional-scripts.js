/**
 * Conditional behavior for scripts that are only loaded when consent is given.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';

  /**
   * Array bag for all loaded scripts.
   *
   * @type {Array}
   */
  var alreadyLoaded = [];

  /**
   * Enables the 'Conditional scripts' functionality.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches the 'Conditional scripts' behavior.
   */
  Drupal.behaviors.burdaCmpLiveRampConditionalScripts = {
    attach: function attach(context) {
      var self = this;

      // Initialize when CMP library is ready.
      __tcfapi('addEventListener', null, function () {
        $('body', context).once('burdaCmpLiveRampConditionalScripts').each(function () {
          if (typeof drupalSettings.burdaCmp !== 'undefined') {
            for (var extension in drupalSettings.burdaCmp) {
              if (drupalSettings.burdaCmp.hasOwnProperty(extension)) {
                var files = drupalSettings.burdaCmp[extension];

                for (var url in files) {
                  if (files.hasOwnProperty(url)) {
                    self.checkConsent(url, files[url]);
                  }
                }
              }
            }
          }
        });
      }, 'cmpReady');
    },

    /**
     * Checks if a consent is given for the conditional script.
     *
     * @param {String} url
     *   The url of the JavaScript to load.
     * @param {Object} options
     *   The options for the JavaScript to load (should have a 'vendor' property
     *   at least).
     */
    checkConsent: function(url, options) {
      var self = this;

      // Prepare consent check data.
      var consentData = self.getData(options);

      if (consentData.vendorId) {
        // Check consent based on vendor/purpose(s).
        __tcfapi('checkConsent', null, function (data, success) {
          // Load script?
          if (data) {
            if (alreadyLoaded.indexOf(url) === -1) {
              $.getScript(url, function () {
                alreadyLoaded.push(url);
              });
            }
          }
        }, {
          data: [consentData],
          recheckConsentOnChange: true
        });
      }
    },

    /**
     * Returns the consent data associated with the conditional script.
     *
     * @param {Object} options
     *   A definition object containing information about associated vendor and
     *   purpose(s).
     *
     * @return {Object}
     *   An object containing all related consent data:
     *     - vendorId: Associated vendor ID.
     *     - purposeIds: Array of associated purpose ID(s).
     */
    getData: function getData(options) {
      // Determine required vendor/purpose(s).
      var vendorId = typeof options.vendor !== 'undefined' ? options.vendor : null;
      var purposeIds = typeof options.purposes !== 'undefined' ? options.purposes : null;

      // Prepare consent check data.
      var data = {
        vendorId: vendorId
      };

      if (purposeIds) {
        data.purposeIds = purposeIds;
      }

      return data;
    }
  };

})(jQuery, Drupal, drupalSettings);
