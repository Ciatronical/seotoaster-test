define([
    'underscore',
    'backbone'
], function(_, Backbone){

    var NewsView = Backbone.View.extend({
        tagName: 'li',
        className: 'news-item',
        template: $('#newsItemTemplate').template(),
        events: {
            'click :not(.news-item-delete)' : 'editNewsItem'
        },
        initialize: function() {
            this.model.view = this;
        },
        editNewsItem: function(e) {
            showSpinner();
            if($(e.currentTarget).closest('#manage-posts-container').hasClass('edit-organize-news')){
                var newsUrl = $(e.currentTarget).closest('.news-item').find('.news-item-title').data('url');
                window.location.href = newsUrl;
            }else{
                appRouter.navigate('edit/' + this.model.get('id'), {trigger: true});
                $('#manage-posts-container').hide("slide", { direction: "right"});
            }
        },
        render: function(){
            $(this.el).html($.tmpl(this.template, this.model.toJSON()));
            return this;
        }
    });

    return NewsView;
});