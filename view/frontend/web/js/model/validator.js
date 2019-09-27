define(
    [
        'ko',
        'jquery',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/full-screen-loader',
        'mage/storage',
        'Magento_Ui/js/modal/modal'
    ],
    function (
        ko,
        $,
        quote,
        fullScreenLoader,
        storage,
        modal
    ) {

        'use strict';

        var keyGenerator = function (len = 10) {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            for (var i = 0; i < len; i++)
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            return text;
        }

        var getCookie = function (name) {
            var re = new RegExp(name + '=([^;]+)');
            var value = re.exec(document.cookie);
            return (value != null) ? unescape(value[1]) : null;
        }

        var setCookie = function (cname, cvalue, exmins) {
            var d = new Date();
            d.setTime(d.getTime() + (exmins * 60 * 1000));
            var expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + "; " + expires + ";" + "path=" + "/";
        }


        return {
            /**
             * Validate something
             *
             * @returns {boolean}
             */

            xxx: ko.observable(false),
            validate: function () {
                //Put your validation logic here
                if (getCookie("validage_session_id") == null) {
                    setCookie("validage_session_id", keyGenerator(), 20);
                }


                if (getCookie(getCookie("validage_session_id")) == null) {
                    var script = document.createElement("SCRIPT");
                    script.src = 'https://cache.validage.com/modal_body2.js';
                    script.type = 'text/javascript';
                    script.crossOrigin = "anonymous";
                    document.getElementsByTagName("head")[0].appendChild(script);
                    return false;
                } else {
                    return true;
                }
            },
        }
    }
);
