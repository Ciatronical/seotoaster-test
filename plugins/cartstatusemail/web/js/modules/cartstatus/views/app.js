define([
    'underscore',
    'backbone',
    './status_table.js',
    './status_form.js',
    '../../product/models/product.js',
    '../../product/collections/products_pager.js',
    '../../product/views/productlist.js',
    '../../product/collections/images.js',
    'i18n!../../../../../../shopping/web/js/nls/'+$('input[name=system-language]').val()+'_ln.js'
], function(_, Backbone,
            StatusTableView, StatusFormView, ProductModel, ProductsCollection, ProductListView, ImagesCollection, i18n){
    var MainView = Backbone.View.extend({
        el: $('#status-cart-email'),
        events: {
            'click .show-list': 'toggleList',
            'click #product-list-back-link': 'hideProductList',
            'change #product-list-holder input.marker': 'markProducts',
            'keypress #product-list-search': 'filterProducts',
            'click .paginator .page': 'paginatorAction',
            'click .productlisting a': 'productAction',
            'click #massaction': 'massAction',
            'change #productsRulePrepare':'changedRule'
        },
        templates: {},
        products: null,
        tags: null,
        searchIndex: null,
        websiteUrl: $('#website_url').val(),
        mediaPath: $('#media-path').val(),
        initialize: function(){
            this.$el = $('#status-cart-email');
            var self = this;
            this.initProduct();
            $('#product-list-search').ajaxStart(function(){
                $(this).attr('disabled', 'disabled');
            }).ajaxStop(function(){
                $(this).removeAttr('disabled');
            });
            this.images =  new ImagesCollection(),
            this.images.on('reset', this.renderImages, this);


            this.statusTable = new StatusTableView();
            this.statusTable.render();


            this.statusForm = new StatusFormView();
            this.statusForm.render();

            this.initProducts().pager();


            this.statusForm.$el.on('status:created', _.bind(this.statusTable.render, this.statusTable));
            this.statusTable.on('status:edit', this.markProducts, this);


        },
        initProduct: function () {
            this.model = new ProductModel();

            this.model.on('sync', function(){
                if (this.model.has('options')){
                    this.model.get('options').on('add', this.renderOption, this);
                    this.model.get('options').on('reset', this.renderOptions, this);
                }
                if (this.products !== null){
                    this.products.pager();
                }
                this.render();
                showMessage('Product saved.<br/> Go to your search engine optimized product landing page here.');
            }, this);
            this.model.on('error', this.processSaveError, this);
            this.model.get('options').on('add', this.renderOption, this);
            this.model.get('options').on('reset', this.renderOptions, this);
            return this;
        },
        initProducts: function(){
            if (this.products === null) {
                this.products = new ProductsCollection();
                this.products.bind('add', this.renderProduct, this);
                this.products.bind('reset', this.renderProducts, this);
            }
            return this.products;
        },
        renderProduct: function(product){
            var productView = new ProductListView({model: product});

            this.$('#product-list-holder').append(productView.render().el);
            if (_.has(this.products, 'checked') && _.contains(this.products.checked, product.get('id'))){
                productView.$el.find('input.marker').prop('checked', true);
            }
        },
        renderProducts: function(){
            if (this.products.size()){
                this.$('#product-list-holder').empty();
                this.products.each(this.renderProduct, this);
                var paginatorData = {
                    collection : 'products',
                    cssClass: 'mt5px'
                };
                paginatorData = _.extend(paginatorData, this.products.info());
                $('div.paginator', '#product-list').replaceWith(_.template($('#paginatorTemplate').html(), paginatorData));
            } else {
                $('#product-list-holder').html('<p class="nothing">'+$('#product-list-holder').data('emptymsg')+'</p>');
            }
        },
        markProducts: function (e) {
            var checked = [],
                url = $('#website_url').val(),
                img = url + '/system/images/noimage.png';

            if (this.statusTable.productsInfo || this.statusTable.productsRule === 'without') {
                $.each(this.statusTable.productsInfo, function () {
                    if (this.photo) {
                        img = this.photo.replace(/(.*)\/(.*)/, url + "/media/$1/product/$2");
                    }
                    checked.push(parseInt(this.id));
                    $('#checked-product-images').append('<span class="productChekedImage productChekedImage-' + this.id +
                    '"><img src="' + img + '" alt="' + this.name + '" title="' + this.name + '" /></span>');
                    $("a[data-pid=" + this.id + "]").closest('.productlisting').find('input').prop('checked', true);
                });
                $("input.productsIds").val(checked.toString());
                delete this.statusTable.productsInfo;
                delete this.statusTable.productsRule;
            } else {
                checked = _.has(this.products, 'checked') ? this.products.checked : [],
                    pid = parseInt(e.currentTarget.value),
                    img = e.target.dataset.imageUrl,
                    alt = e.target.dataset.altImg;
                if (e.currentTarget.checked) {
                    checked = _.union(checked, pid);
                    $('#checked-product-images').append('<span class="productChekedImage productChekedImage-' + pid +
                    '"><img src="' + img + '" alt="' + alt + '" title="' + alt + '" /></span>');
                } else {
                    checked = _.without(checked, pid);
                    $('#checked-product-images .productChekedImage-' + pid + '').remove();
                }
            }
            this.products.checked = checked;
        },
        toggleList: function(e) {
            e.preventDefault();
            this.initSearchIndex();

            var listtype = $(e.currentTarget).data('listtype');

            $('#product-list').show('slide');
            $('#product-list-holder').data('type', listtype);
            var labels = $('#massaction').data('labels');
            //$('#massaction').text(labels[listtype]);

            if (this.products === null) {
                $('#product-list-holder').html('<div class="spinner"></div>');
                return this.initProducts().pager();
            }

        },
        hideProductList: function(){
            $('#product-list').hide('slide');
            //$('.productsIds').val('');
            var term = $.trim($('#product-list-search').val());
            if (term != this.products.key){
                if (term == ''){
                    $('#product-list-search').trigger('keypress', true);
                } else {
                    $('#product-list-search').val(this.products.key);
                }
            }
        },
        massAction: function() {
            var type = $('#product-list-holder').data('type');

            if (!_.has(this.products, 'checked') || _.isEmpty(this.products.checked)){
                return false;
            }
            $('.productsIds').val(this.products.checked);
            $('#product-list').hide('slide');
            $('#checked-product-images').show();

            //$('div.productlisting input.marker:checked', '#product-list-holder').removeAttr('checked');
            //this.products.checked = [];

            return false;
        },
        initSearchIndex: _.once(function(){
            $.getJSON($('#website_url').val() + '/plugin/shopping/run/searchindex', function(response){
                self.searchIndex = response;
                $('#product-list-search').autocomplete({
                    minLength: 2,
                    source: response,
                    select: function(event, ui){
                        $('#product-list-search').val(ui.item.value).trigger('keypress', true);
                    }
                });
            });
        }),
        paginatorAction:  function(e){
            var page = $(e.currentTarget).data('page');
            var collection = $(e.currentTarget).parent('.paginator').data('collection');
            if (!collection) return false;
            if (_.has(this, collection)){
                collection = this[collection];
            }

            switch (page) {
                case 'first':
                    collection.goTo(collection.firstPage);
                    break;
                case 'prev':
                    if (collection instanceof Backbone.Paginator.requestPager){
                        collection.requestPreviousPage();
                    } else {
                        collection.previousPage();
                    }
                    break;
                case 'next':
                    if (collection instanceof Backbone.Paginator.requestPager){
                        collection.requestNextPage();
                    } else {
                        collection.nextPage();
                    }
                    break;
                case 'last':
                    collection.goTo(collection.totalPages);
                    break;
                default:
                    var pageId = parseInt(page);
                    !_.isNaN(pageId) && collection.goTo(pageId);
                    break;
            }
            return false;
        },
        filterProducts: function(e, forceRun) {
            if (e.keyCode === 13 || forceRun === true) {
                $('#product-list-holder').html('<div class="spinner"></div>');
                this.products.key = e.currentTarget.value;
                this.products.goTo(this.products.firstPage);
                $(e.target).autocomplete('close');
            }
        },
        productAction:function(e){
            e.preventDefault();
            $(e.currentTarget).closest('.productlisting').find('.marker').trigger('click');

        },
        changedRule:function(e){
            var rules = $(e.currentTarget).val();
            if(rules == 'with'){
                $('#productsRule').show();
                $('.prodInfoLabel').show();
                $('.show-list').show();
                $('#checked-product-images').show();
            }else{
                $('#productsRule').hide();
                $('.prodInfoLabel').hide();
                $('.show-list').hide();
                $('#checked-product-images').hide();
            }

        }

    });

    return MainView;
});