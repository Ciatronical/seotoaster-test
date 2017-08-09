define([
	'backbone'
], function(Backbone){
    var StatusFormView = Backbone.View.extend({
        el: $('#add-edit-status-cart'),
        events: {
            'submit': 'submit'
        },
        templates: {

        },
        initialize: function(){
            console.log('render form');
            this.$el = $('#add-edit-status-cart');
            this.$el.attr('action', $('#website_url').val()+'api/cartstatusemail/cartstatus/');

        },
        render: function(){
            this.$el = $('#add-edit-status-cart');
            this.$el.attr('action', $('#website_url').val()+'api/cartstatusemail/cartstatus/');
            return this;
        },
        submit: function(e){
            e.preventDefault();
            var self = this,
                form = $(e.currentTarget),
                isValid = true;

            _.each(form.find('.required'), function(el){
                if (!$(el).val()){
                    isValid = false;
                }
            });

            if (!isValid){
                showMessage('Missing required field', true);
                return false;
            }
            showSpinner();
            var data = this.$el.serialize();
            var periodHours = $('#periodHours').val();
            var cartStatus  = $('#cart-status :selected').val();
            var productsRulePrepare = $('#productsRulePrepare :selected').val();
            var productsRule = $('#productsRule :selected').val();
            var emailTemplate = $('.emailTemplate :selected').val();
            var emailFrom = $('#emailFrom').val();
            var emailMessage = $('#emailMessage').val();
            var productsIds = $('.productsIds').val();
            var secureToken = $('.secureToken').val();
            var cartId = $('#cartId').val();
            if(productsRulePrepare == 'without'){
                productsRule = productsRulePrepare;
            }

            $.ajax({
                url: this.$el.attr('action'),
                data: {
                    periodHours:periodHours,
                    cartStatus:cartStatus,
                    productsRule:productsRule,
                    emailTemplate:emailTemplate,
                    emailFrom:emailFrom,
                    emailMessage:emailMessage,
                    productsIds:productsIds,
                    secureToken:secureToken,
                    cartId:cartId
                },
                type: 'POST',
                dataType: 'json'
            }).done(function(response) {
                    self.$el.trigger('reset');
                    self.$el.trigger('status:created');
                    $('.productsIds').val('');
                    $('#cartId').val('');
                    $('.productChekedImage').remove();
                    $('#productsRule').show();
                    $('.prodInfoLabel').show();
                    $('.show-list').show();
                    $('input.marker').attr('checked', false);
                    hideSpinner();
                    showMessage('Saved');

            }).fail(function(response){
                    hideSpinner();
                    showMessage(response.responseText, true);
            });

        },
        validate: function(e){
            var el = $(e.currentTarget);
            console.log(el.data());
        }

    });

    return StatusFormView;
});