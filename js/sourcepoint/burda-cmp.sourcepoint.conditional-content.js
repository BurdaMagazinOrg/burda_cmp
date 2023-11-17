/**
 * Conditional behavior for content that is only loaded when consent is given.
 */

(function (Drupal, $, once, drupalSettings) {

  'use strict';

  /**
   * Enables the 'Conditional content' functionality on HTML elements.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches the 'Conditional content' behavior.
   */
  Drupal.behaviors.burdaCmpSourcepointConditionalContent = {
    attach: function attach(context) {
      var self = this;

      // Initialize when CMP library is ready.
      __tcfapi('addEventListener', 2, function (tcData, success) {
        $('[data-burda-cmp-conditional-content]', context).once('burdaCmpSourcepointConditionalContent').each(function () {
          var $self = $(this);
          self.checkConsent($self);

          // Initialize toggle (if any).
          $self.find('[data-burda-cmp-toggle]').on('click.burdaCmpSourcepointConditionalContent', function (e) {
            e.preventDefault();
            e.stopPropagation();
            self.toggleConsent($self);
          });

          // Initialize settings button (if any).
          $self.find('[data-burda-cmp-show-consent-manager]').on('click.burdaCmpSourcepointConditionalContent', function (e) {
            e.preventDefault();
            e.stopPropagation();

            self.showConsentManager($self);
          });
        });
        // In case user initially confirms CMP on this page.
        if (success && tcData.eventStatus === 'useractioncomplete') {
          $('[data-burda-cmp-conditional-content]').each(function () {
            var $self = $(this);
            self.checkConsent($self);
          });
        }
      });
    },

    /**
     * Checks if a consent is given for the conditional content.
     *
     * @param {jQuery} $element
     *   A jQuery DOM fragment that represents the conditional content element.
     */
    checkConsent: function ($element) {
      var self = this;
      var $placeholder = self.getWrapperPlaceholder($element);
      var $injectedContent = self.getWrapperInjectedContent($element);

      // Prepare consent check data.
      var consentData = self.getData($element);
      if (consentData.vendorId || consentData.purposeIds) {
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
            // If at least one of them was given, render injected content.
            if (consentForVendorWasGiven || consentForPurposeWasGiven) {
              if (!self.isContentInjected($element)) {
                self.activateContent($element);
              }
            } else {
              self.disableContent($element);
            }
          } else {
            // Show placeholder in case there is no success in getCustomVendorConsents call.
            Drupal.detachBehaviors($injectedContent.get(0), drupalSettings);
            $injectedContent.html('');
            $placeholder.show();
            $element.attr('aria-expanded', 'false');
          }
        });
      }
    },

    /**
     * Returns the actual conditional content markup to inject.
     *
     * @param {jQuery} $element
     *   A jQuery DOM fragment that represents the conditional content element.
     *
     * @return {String}
     *   The HTML markup for conditional content to inject when consent is
     *   given.
     */
    getContent: function getContent($element) {
      return $element.attr('data-burda-cmp-conditional-content');
    },

    /**
     * Returns the consent data associated with the conditional content element.
     *
     * @param {jQuery} $element
     *   A jQuery DOM fragment that represents the conditional content element.
     *
     * @return {Object}
     *   An object containing all related consent data:
     *     - vendorId: Associated vendor ID.
     *     - purposeIds: Array of associated purpose ID(s).
     */
    getData: function getData($element) {
      // Determine required vendor/purpose(s).
      var vendorId = this.getVendorId($element);
      var purposeIds = this.getPurposeIds($element);

      // Prepare consent check data.
      var data = {
        vendorId: vendorId,
      };

      if (purposeIds) {
        data.purposeIds = purposeIds;
      }

      return data;
    },

    /**
     * Returns the vendor ID to check consent for.
     *
     * @param {jQuery} $element
     *   A jQuery DOM fragment that represents the conditional content element.
     *
     * @return {String}
     *   The related vendor ID.
     */
    getVendorId: function getVendorId($element) {
      return $element.attr('data-burda-cmp-vendor');
    },

    /**
     * Returns the purpose ID(s) to check consent for.
     *
     * @param {jQuery} $element
     *   A jQuery DOM fragment that represents the conditional content element.
     *
     * @return {Array}
     *   An array containing all related purpose ID(s).
     */
    getPurposeIds: function getPurposeIds($element) {
      var purposeIds = $element.attr('data-burda-cmp-purposes');
      purposeIds = !purposeIds ? null : purposeIds.split(',').map(function (item) {
        return $.trim(item);
      });
      return purposeIds;
    },

    /**
     * Returns the wrapper element for injected content.
     *
     * @param {jQuery} $element
     *   A jQuery DOM fragment that represents the conditional content element.
     *
     * @return {jQuery}
     *   A jQuery DOM fragment that represents the wrapper for injected
     *   conditional content.
     */
    getWrapperInjectedContent: function getWrapperInjectedContent($element) {
      return $element.find('[data-burda-cmp-injected-content]');
    },

    /**
     * Returns the placeholder wrapper element.
     *
     * @param {jQuery} $element
     *   A jQuery DOM fragment that represents the conditional content element.
     *
     * @return {jQuery}
     *   A jQuery DOM fragment that represents the placeholder element.
     */
    getWrapperPlaceholder: function getWrapperPlaceholder($element) {
      return $element.find('[data-burda-cmp-conditional-content-placeholder]');
    },

    /**
     * Returns whether the conditional content has been injected.
     *
     * @param {jQuery} $element
     *   A jQuery DOM fragment that represents the conditional content element.
     *
     * @return {bool}
     *   Whether the conditional content has been injected.
     */
    isContentInjected: function isContentInjected($element) {
      var $wrapper = this.getWrapperInjectedContent($element);

      return !$wrapper.is(':empty');
    },

    /**
     * Shows the consent manager.
     *
     * @param {jQuery} $element
     *   A jQuery DOM fragment that represents the conditional content element.
     */
    showConsentManager: function showConsentManager($element) {
      window._sp_.gdpr.loadPrivacyManagerModal(drupalSettings.burdaCmp.privacyManagerId);
    },

    /**
     * Toggles the consent for the conditional content.
     *
     * @param {jQuery} $element
     *   A jQuery DOM fragment that represents the conditional content element.
     */
    toggleConsent: function toggleConsent($element) {
      var self = this;
      // Prepare consent check data.
      var consentData = this.getData($element);
      if (consentData.vendorId) {
        // Rewrite consent data to have vendor ID as array.
        consentData.vendorIds = [consentData.vendorId];
        delete consentData.vendorId;

        if (this.isContentInjected($element)) {
          // Remove purpose IDs to only reject specific vendor but not all of
          // its purposes.
          delete consentData.purposeIds;
          // @TODO Remove given consent to sourcepoint. At which point can this
          //  be the case? Because when consent is given, the button will not
          //  be rendered.
          // __tcfapi('reject', null, function () {}, consentData);
        } else {
          // Send given consent to sourcepoint. Only the vendor.
          // See https://sourcepoint-public-api.readme.io/reference/postcustomconsent#command
          // __tcfapi('postCustomConsent', 2, callback_fn, [vendorIds], [purposeIds], [legitimateInterestPurposeIds] )
          __tcfapi('postCustomConsent', 2, (data, success) => {
            if (success) {
              self.activateContent($element);
            }
          }, consentData.vendorIds, consentData.purposeIds, []);

        }
      }
    },
    activateContent: function activateContent($element) {
      var self = this;
      var $placeholder = self.getWrapperPlaceholder($element);
      var $injectedContent = self.getWrapperInjectedContent($element);
      $injectedContent.append(self.getContent($element));
      $placeholder.hide();
      Drupal.attachBehaviors($injectedContent.get(0), drupalSettings);
      $element.attr('aria-expanded', 'true');
    },
    disableContent: function disableContent($element) {
      var self = this;
      var $placeholder = self.getWrapperPlaceholder($element);
      var $injectedContent = self.getWrapperInjectedContent($element);
      Drupal.detachBehaviors($injectedContent.get(0), drupalSettings);
      $injectedContent.html('');
      $placeholder.show();
      $element.attr('aria-expanded', 'false');
    },
  };

})(Drupal, jQuery, once, drupalSettings);
