define(
    [
        'ko',
        'jquery',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_Checkout/js/model/quote',
        'Magento_Ui/js/modal/modal'
    ],
    function (
        ko,
        $,
        quote,
        fullScreenLoader,
        modal
    ) {
        // var options = {
        //     type: 'popup',
        //     responsive: true,
        //     innerScroll: true,
        //     modalClass: 'custom-popup-modal',
        //     buttons: [{
        //         text: $.mage.__('Close'),
        //         class: '',
        //         click: function () {
        //             this.closeModal();
        //         }
        //     }]
        // };

        // var popup = modal(options, $('#custom-popup-modal'));
        // $( document ).ready(function() {
        //     $('#custom-popup-modal').modal('openModal');
        // });
        'use strict';
        return {
            /**
             * Validate something
             *
             * @returns {boolean}
             */

            xxx: ko.observable(false),
            validate: function () {
                //Put your validation logic here
                //alert("popup");

                if (this.xxx() == true) { return true }
                debugger;
                console.log(quote.billingAddress());
                this.showMyModal(this.xxx);
                return false;


                // return true;
            },
            showMyModal: function (xxx) {
                //var ageVerifyContent ='<input type="text" name="from_date" id="page-from-date" title="From" data-mage-init=' +  "'{"+'"calendar": {"showTime": false}}'+"' />";
                //var dob = '<p style=" width: auto;" ><span>Please Enter Your Date of Birth:</span><input style=" width: auto;" size="2" type="text" name="month_date" id="month-date" /><span>/</span><input style=" width: auto;"  size="2" type="text" name="day_date" id="day-date" /><span>/</span><input style=" width: auto;"  size="4" type="text" name="year_date" id="year-date" /></p>';
                var dob = '<p style=" width: auto;" ><span>Please Enter Your Date of Birth : </span><input style=" width: auto; text-align:center;" size="11" type="date" name="dateOfBirth" id="dateOfBirth" /></p>';
                $('<body>').html(dob).modal({
                    title: 'Age Verification',
                    autoOpen: true,
                    // closed: function () {
                    //     // on close
                    //     alert('gggg');
                    //   // return true;
                    // },
                    buttons: [{
                        text: 'Confirm',
                        // attr: {
                        //     'data-action': 'confirm'
                        // },
                        //class: 'action-primary',
                        click: function () {
                            this.closeModal();
                            var payload = {
                                billingAddress: quote.billingAddress(),
                                shippingAddress: quote.shippingAddress(),
                                quoteId: quote.getQuoteId(),
                                emailAddress: quote.guestEmail,
                                dob: document.getElementById("dateOfBirth").value
                            }
                            if (checkoutConfig.isCustomerLoggedIn) {
                                payload.emailAddress = customerData.email;
                                payload.customerData = customerData;
                            }
                            console.log(payload);
                            // data = { 'session_data': payload };
                            // storage.post(
                            //     "rest/V1/xcode/api/easy_check",
                            //     JSON.stringify(data),
                            //     true
                            // ).done(
                            //     function (response) {
                            //         var responsex = JSON.parse(response);
                            //         if (responsex.code == "201") {
                            //             debugger;
                            //         } else {
                            //             debugger;
                            //         }
                            //         fullScreenLoader.stopLoader();
                            //     }
                            // ).fail(
                            //     function (response) {
                            //         alert("Could not communicate to the server. Plese try again");
                            //         fullScreenLoader.stopLoader();
                            //     }
                            // );
                            //xxx(true);
                            $(".payment-method._active").find('.action.primary.checkout').trigger('click');

                        }
                    }]
                });

            }
        }
    }
);
