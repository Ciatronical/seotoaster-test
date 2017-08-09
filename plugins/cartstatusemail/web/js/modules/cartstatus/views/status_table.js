define([
    'underscore',
    'backbone',
    '../collections/status.js',
    'i18n!../../../../../../shopping/web/js/nls/'+$('input[name=system-language]').val()+'_ln.js',
    $('#website_url').val()+'system/js/external/jquery/plugins/DataTables/jquery.dataTables.min.js'
], function(_,Backbone,
            StatusCollection,i18n
            ){

    var StatusTableView = Backbone.View.extend({
        el: $('#status-cart-email-table'),
        events: {
            'click a[data-role=delete]': 'deleteStatusCart',
            'click a[data-role=edit]'  : 'editStatusCart'
        },
        templates: {},
        dataTable: null,
        initialize: function(options){

            var aoColumnDefs = [
                { "bSortable": false, "aTargets": [ -1 ] }
            ];

            this.$el = $('#status-cart-email-table').dataTable({
                'sDom': 't<"clearfix"p>',
                "iDisplayLength": 5,
                "bPaginate": true,
                "bAutoWidth": false,
                "aoColumnDefs": aoColumnDefs,
                "oLanguage": {
                    "oPaginate": {
                        "sNext": _.isUndefined(i18n['Next'])?'Next':i18n['Next'],
                        "sPrevious": _.isUndefined(i18n['Previous'])?'Previous':i18n['Previous']
                    }
                }
            });

            this.status = new StatusCollection();

            this.status.on('reset', this.renderStatuses, this);
            this.status.on('add', this.renderStatuses, this);
            this.status.on('destroy', this.renderStatuses, this);


        },
        render: function(){
            this.status.pager();
        },
        renderStatuses: function(){
            this.$el.fnClearTable();
            this.status.each(this.renderStatus, this);

        },
        renderStatus: function(status){
            var cartStatus = $('#cart-status option[value="'+status.get('cartStatus')+'"]').html();
            var emailTemplate = '';
            var productIdsResult = '<span>';
            var productIdsArray = status.get('productsIds').split(',');
            var productIdsQuantity = productIdsArray.length;
            var productRule = '';
            $.each(productIdsArray, function(index, prodId) {
               if (status.get('productsRule') != 'without' ){
                   if(typeof status.get('prodData')[prodId] !== 'undefined'){
                       productIdsResult += '<a target="_blank" href="'+$('#website_url').val()+status.get('prodData')[prodId]['pageUrl']+'">'+prodId+'</a> ';
                   }else{
                       productIdsResult += prodId+' ';
                   }
                   if(productIdsQuantity-1 < index){
                       productIdsResult += ',';
                   }
                }
            });
            productIdsResult +='</span>';

            if (status.get('productsRule') == 'without' ){
                productIdsResult = '';
            }

            if(status.get('emailTemplate') != '0' ){
                emailTemplate = '<span>'+status.get('emailTemplate')+'</span>';
            }


            if (status.get('productsRule') == 'without' ){
                productRule = 'any cart content';
            }

            if (status.get('productsRule') == 'all' ){
                productRule = 'incl. all';
            }

            if (status.get('productsRule') == 'any' ){
                productRule = 'incl. any';
            }

            this.$el.fnAddData([
                '<span>'+cartStatus+'</span>',
                '<span>'+productRule+'</span> '+productIdsResult,
                '<span>'+status.get('periodHours')+'</span>',
                emailTemplate,
                '<a class="ticon-remove error icon14 fl-right" data-role="delete" data-cid="'+status.get('id')+'" href="javascript:;"></a>' +
                '<a class="ticon-edit icon14 fl-right" data-role="edit" data-cid="'+status.get('id')+'" href="javascript:;"></a>'
            ]);
            $("#blue-btn").val("Create");
        },
        deleteStatusCart: function(e){
            var cid = $(e.currentTarget).data('cid');
            var model = this.status.get(cid);
            showConfirm("Are you sure you want to delete the email?", function() {
                if (model){
                    model.destroy();
                }
            })
        },
        editStatusCart: function (e) {
            $("#product-list").find('input[type=checkbox]').prop("checked", false);
            $('#checked-product-images').empty();
            var cid = $(e.currentTarget).data('cid');
            var self = this;
            $.ajax({
                url: $('#website_url').val() + 'api/cartstatusemail/cartstatus/id/' + cid,
                type: 'GET',
                dataType: 'json'
            }).done(function (response) {
                var response = response[0], productsRule = $('#productsRule'),
                    productsRulePrepare = $('#productsRulePrepare'), prodInfoLabel = $('.prodInfoLabel'),
                    showList = $('.show-list'), checkedProductImages = $('#checked-product-images');
                if (response.productsRule !== "without") {
                    productsRulePrepare.val("with");
                    productsRule.val(response.productsRule);
                    productsRule.show();
                    prodInfoLabel.show();
                    showList.show();
                    checkedProductImages.show();
                } else {
                    productsRulePrepare.val("without");
                    productsRule.hide();
                    prodInfoLabel.hide();
                    showList.hide();
                    checkedProductImages.hide();
                }
                $('#periodHours').val(response.periodHours);
                $('#cart-status').val(response.cartStatus);
                $('.emailTemplate').val(response.emailTemplate);
                $('#emailFrom').val(response.emailFrom);
                $('#emailMessage').val(response.emailMessage);
                $("#cartId").val(response.id);
                $("#blue-btn").val("Update");
                self.productsRule = response.productsRule;
                self.productsInfo = response.prodData;
                self.trigger('status:edit');
            })
        }
    });

    return StatusTableView;
});