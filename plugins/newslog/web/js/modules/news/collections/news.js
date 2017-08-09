define([
    'underscore',
    'backbone',
    '../models/news',
    'backbone.paginator'
], function(_, Backbone, NewsModel){

    var NewsList = Backbone.Paginator.requestPager.extend({
        model: NewsModel,
        order : 'desc',

        paginator_core: {
            dataType : 'json',
            url      : $('#website_url').val() + '/api/newslog/news/'
        },

        paginator_ui: {
            firstPage   : 0,
            currentPage : 0,
            perPage     : 24,
            totalPages  : 10
        },

        server_api: {
            count: true,
            order: function() { return this.order; },
            limit: function() { return this.perPage; },
            offset: function() { return this.currentPage * this.perPage }
        },
        parse: function (response) {
            this.totalRecords = (this.server_api.count) ? response.total : response.length;
            this.totalPages   = Math.floor(this.totalRecords / this.perPage);
            return (this.server_api.count) ? response.data : response;
        }

    });

    return NewsList;
});