/**
 * Conditional behavior for content that is only loaded when consent is given.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';

  /**
   * Enables the 'Conditional content' functionality on HTML elements.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches the 'Conditional content' behavior.
   */
  Drupal.behaviors.burdaCmpLiveRampConditionalContent = {
    attach: function attach(context) {
      var self = this;

      // Initialize when CMP library is ready.
      __tcfapi('addEventListener', null, function () {
        $('[data-burda-cmp-conditional-content]', context).once('burdaCmpLiveRampConditionalContent').each(function () {
          var $self = $(this);

          self.checkConsent($self);

          // Initialize toggle (if any).
          $self.find('[data-burda-cmp-toggle]').on('click.burdaCmpLiveRampConditionalContent', function (e) {
            e.preventDefault();
            e.stopPropagation();

            self.toggleConsent($self);
          });

          // Initialize settings button (if any).
          $self.find('[data-burda-cmp-show-consent-manager]').on('click.burdaCmpLiveRampConditionalContent', function (e) {
            e.preventDefault();
            e.stopPropagation();

            self.showConsentManager($self);
          });
        });
      }, 'cmpReady');
    },

    /**
     * Checks if a consent is given for the conditional content.
     *
     * @param {jQuery} $element
     *   A jQuery DOM fragment that represents the conditional content element.
     */
    checkConsent: function($element) {
      var self = this;
      var $placeholder = self.getWrapperPlaceholder($element);
      var $injectedContent = self.getWrapperInjectedContent($element);

      // Prepare consent check data.
      var consentData = self.getData($element);

      if (consentData.vendorId) {
        // Check consent based on vendor/purpose(s).
        __tcfapi('checkConsent', null, function (data, success) {
          if (data) {
            if (!self.isContentInjected($element)) {
              $injectedContent.append(self.getContent($element));
              $placeholder.hide();
              Drupal.attachBehaviors($injectedContent.get(0), drupalSettings);
              $element.attr('aria-expanded', 'true');
            }
          }
          else {
            Drupal.detachBehaviors($injectedContent.get(0), drupalSettings);
            $injectedContent.html('');
            $placeholder.show();
            $element.attr('aria-expanded', 'false');
          }
        }, {
          data: [consentData],
          recheckConsentOnChange: true
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
        vendorId: vendorId
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
     * @return {Number}
     *   The related vendor ID.
     */
    getVendorId: function getVendorId($element) {
      return Number($element.attr('data-burda-cmp-vendor'));
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
        return Number($.trim(item));
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
      __tcfapi('showConsentManager', null, function () {});
    },

    /**
     * Toggles the consent for the conditional content.
     *
     * @param {jQuery} $element
     *   A jQuery DOM fragment that represents the conditional content element.
     */
    toggleConsent: function toggleConsent($element) {
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

          __tcfapi('reject', null, function () {}, consentData);
        }

        else {
          __tcfapi('accept', null, function () {}, consentData);
        }
      }
    }
  };

})(jQuery, Drupal, drupalSettings);
