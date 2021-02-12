/**
 * Function to disable CMP if need. i.e. on privacy policy or
 * imprint pages. We can not use behaviours here.
 */

(function (drupalSettings) {

  'use strict';

  __tcfapi('addEventListener', 2, function (tcData, success) {
    if (drupalSettings.hasOwnProperty('burdaCmp')) {
      if (drupalSettings.burdaCmp.hasOwnProperty('disablePrivacyManager')) {
        if (drupalSettings.burdaCmp.disablePrivacyManager) {
          __tcfapi('toggleConsentTool', 2, function () {}, false);
        }
      }
    }
  }, 'consentNoticeDisplayed');
})(drupalSettings);
