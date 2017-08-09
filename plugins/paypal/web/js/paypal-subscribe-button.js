$(function(){
    $(".paypal-submit").on('click', function(e){
        e.preventDefault();
        var self = $(this).parent("form");
        $.ajax({
            type: "POST",
            url: $('.websiteUrl').val()+'plugin/paypal/run/getSubscribeData/',
            dataType: "json",
            async:false,
            data: {
                paymentPeriod:self.find('.subscribe-payment-period').val(),
                paymentQty: self.find('.subscribe-payment-qty').val(),
                subscribeCycle: self.find('.subscribe-payment-subscribe-cycle').val(),
                cartId: self.find('.cartId').val()
            },
            success: function(response){
                if(response.error == 1){
                    window.location = $('.websiteUrl').val()+'plugin/shopping/run/thankyou/';
                }else{
                    self.find('.subscribe-amount').val(response.responseText.total);
                    self.submit();
                }
            }
        });
    });
});