define([
    'underscore',
    'backbone',
    './views/app',
    './collections/tags',
    './views/tag',
    './models/news',
    './collections/news',
    './views/news',
    './views/website'
], function(_, Backbone, AppView, TagsCollection, TagView, NewsModel, NewsCollection, NewsView, WebsiteView) {

    var NewsRouter = Backbone.Router.extend({

        routes: {
            ''         : 'createNewPost',
            'new'      : 'createNewPost',
            'edit/:id' : 'editPost',
            'manage'   : 'managePosts',
            'broadcast/:id' : 'broadcastPost'
        },

        app   : null,
        tags  : null,
        posts : null,
        broadcastSites: null,

        initialize: function() {

            this.app = new AppView();

            this.tags = new TagsCollection();
            this.tags.on('add', this.renderTag, this);
            this.tags.on('reset', this.renderTags, this);

            this.posts = new NewsCollection();
            this.posts.on('reset', this.renderPosts, this);

            //extending news collection api for the search
            this.posts.server_api = _.extend(this.posts.server_api, {
                search: function() {return $('#search').val()}
            });
        },
        renderTag : function(tag, index) {
            var view = new TagView({model: tag});
            view.render();
            if (index instanceof Backbone.Collection){
                $('#news-tags').prepend(view.$el);
            } else {
                $('#news-tags').append(view.$el);
            }
            checkboxRadioStyle();
        },
        renderTags: function() {
            $('#news-tags').empty();
            this.tags.each(this.renderTag, this);
        },
        renderPosts: function() {
            $('#manage-posts').empty();
            this.posts.each(function(newsModel) {
                var newsView = new NewsView({model: newsModel});
                $('#manage-posts').append(newsView.render().$el)
            }, this);
            this.renderPager();
        },
        renderPager: function() {
            hideSpinner();
            var pager = _.template($('#pager-template').text());
            $('#pager').html(pager(this.posts.info()));
            return this;
        },
        createNewPost: function() {
            //$('#manage-posts-container:visible').hide('slide');
            this.app.setModel(new NewsModel());
        },
        editPost: function(id) {
            showSpinner();
            var post = new NewsModel();
            post.fetch({data: {id: id}}).done(function() {
                hideSpinner();
                appRouter.app.setModel(post);
            });
        },
        managePosts: function() {
            this.app.$('#manage-posts-container').show("slide", { direction: "right"});
            this.posts.pager({reset: true});
        },
        navigateAction: function(e) {
            e.preventDefault();
            var page = $(e.currentTarget).data('page');
            showSpinner();
            if ($.isNumeric(page)) {
                this.posts.goTo(page, {reset:true});
            } else {
                switch(page){
                    case 'first':
                        this.posts.goTo(this.posts.firstPage,{reset:true});
                        break;
                    case 'last':
                        this.posts.goTo(this.posts.totalPages, {reset:true});
                        break;
                    case 'prev':
                        this.posts.requestPreviousPage({reset:true});
                        break;
                    case 'next':
                        this.posts.requestNextPage({reset:true});
                        break;
                }
            }
        },
        broadcastPost: function(id) {
            var post = new NewsModel();
            showSpinner();
            post.fetch({data: {id: id}}).done(function() {
                appRouter.app.setModel(post);
                $.when(appRouter.app.$('#broadcast-list-container').slideToggle()).done(function() {
                    $.getJSON($('#website_url').val() + 'api/newslog/broadcast/').done(function(response) {
                        hideSpinner();
                        appRouter.broadcastSites = response;
                        appRouter.renderBroadcastSites(id);
                    });
                });
            });
        },
        renderBroadcastSites: function(id) {
            $('#broadcast-list').empty();
            _.each(this.broadcastSites, function(site) {
                var view = new WebsiteView({model: site});
                $('#broadcast-list').append(view.render().$el);
            })
            $('<input id="broadcast-btn" class="btn" type="submit" name="broadcast" value="Broadcast" data-nid="' + id + '" />').insertAfter('#broadcast-list');
        },
        newsDelete: function(e){
            e.preventDefault();
            var newsId = $(e.currentTarget).data('news-id');
            var model = this.posts.get(newsId);
            showConfirm('Do you want delete news item?', function() {
                showSpinner();
                model.destroy({
                    success: function() {
                        hideSpinner();
                        appRouter.posts.pager({reset: true});
                    },
                    error: function(model, response) {
                        hideSpinner();
                    }
                });
            });
        }
    });

    var initialize = function() {
        window.appRouter = new NewsRouter;
        $.when(
            appRouter.tags.fetch({reset:true})
        ).then(function(){
            Backbone.history.start();
        });
    };

    return {
        initialize: initialize
    };
});