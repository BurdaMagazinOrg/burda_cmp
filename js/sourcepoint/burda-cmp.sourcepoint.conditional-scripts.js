/**
 * Conditional behavior for scripts that are only loaded when consent is given.
 */

(function (Drupal, $, once, drupalSettings) {

  'use strict';

  /**
   * Enables the 'Conditional scripts' functionality.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches the 'Conditional scripts' behavior.
   */
  Drupal.behaviors.burdaCmpSourcepointConditionalScripts = {
    attach: function attach(context) {
      var self = this;
      // Initialize when CMP library is ready.
      __tcfapi('addEventListener', 2, function (tcData, success) {
        if (success) {
          // Handles scripts which are attached as elements, e.g.
          // inline scripts at page header and scripts attached as libraries.
          $('[data-burda-cmp-conditional-scripts]', context).once('burdaCmpSourcepointConditionalScripts').each(function () {
            var $self = $(this);
            self.checkConsent({ vendorId: $self.data('burda-cmp-vendorid'), purposeIds: $self.data('burda-cmp-purposeids') });
          });
          // In case user initially confirms CMP on this page.
          if (tcData.eventStatus === 'useractioncomplete') {
            $('[data-burda-cmp-conditional-scripts]', context).each(function () {
              var $self = $(this);
              self.checkConsent({ vendorId: $self.data('burda-cmp-vendorid'), purposeIds: $self.data('burda-cmp-purposeids') });
            });
          }
        }
      });
    },

    /**
     * Checks if a consent is given for the conditional script.
     *
     * @param {Object} options
     *   The options for the JavaScript to load (should have a 'vendorId' property
     *   at least).
     */
    checkConsent: function (options) {
      var self = this;

      // Prepare consent check data.
      var consentData = self.getData(options);

      if (consentData.vendorId) {
        // Check consent based on vendor/purpose(s).
        __tcfapi('getCustomVendorConsents', 2, function (data, success) {
          if (success) {
            let consentForVendorWasGiven = false;
            let consentForPurposeWasGiven = false;
            // Check for Vendor Consent.
            if (Array.isArray(data.consentedVendors)) {
              data.consentedVendors.forEach((vendor) => {
                if (vendor._id === consentData.vendorId) {
                  consentForVendorWasGiven = true;
                }
              });
            }
            // Check for Purpose Consent.
            if (Array.isArray(data.consentedPurposes)) {
              consentForPurposeWasGiven = data.consentedPurposes.some(item => consentData.purposeIds.includes(item._id));
            }
            // If at least one of them was given, load script.
            if (consentForVendorWasGiven || consentForPurposeWasGiven) {
              self.loadVendorScript(consentData.vendorId);
            }
          }
        });
      }
    },

    /**
     * Returns the consent data associated with the conditional script.
     *
     * @param {Object} options
     *   A definition object containing information about associated vendorId and
     *   purposeId(s).
     *
     * @return {Object}
     *   An object containing all related consent data:
     *     - vendorId: Associated vendor ID.
     *     - purposeIds: Array of associated purpose ID(s).
     */
    getData: function getData(options) {
      // Determine required vendor/purpose(s).
      var vendorId = typeof options.vendorId !== 'undefined' ? options.vendorId : null;
      var purposeIds = typeof options.purposeIds !== 'undefined' ? options.purposeIds : null;

      // Prepare consent check data.
      var data = {
        vendorId: vendorId,
      };

      if (purposeIds) {
        data.purposeIds = purposeIds;
      }

      return data;
    },

    loadVendorScript: function loadVendorScript(vendorId) {
      $('script[data-burda-cmp-vendorid="' + vendorId + '"]').each(function () {
        const clone = this.cloneNode(true);
        $(this).remove();
        const $el = $(clone);
        const dataSrc = $el.data('src');
        if (dataSrc) {
          $el.attr('src', dataSrc);
        }
        $el.attr('type', 'application/javascript');
        $el.appendTo($('body'));
      });
    },
  };

})(Drupal, jQuery, once, drupalSettings);
