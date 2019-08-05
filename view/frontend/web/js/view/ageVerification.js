define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/additional-validators',
        'XCode_PaymentAgeVerification/js/model/validator'
    ],
    function (Component, additionalValidators, validator) {
        'use strict';
        additionalValidators.registerValidator(validator);
        return Component.extend({});
    }
);
