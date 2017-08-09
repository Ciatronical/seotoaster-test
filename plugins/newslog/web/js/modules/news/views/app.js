define([
    'underscore',
    'backbone'
], function(_, Backbone){

    var appView = Backbone.View.extend({
        el: $('#new-post'),
        oldUrl: null,
        events: {
            'click #published'        : 'published',
            'keypress input#news-tag' : 'newTag',
            'change #templatelist' : 'selectTemplate',
            'click #event'            : 'toggleEvent',
            'click #save-btn'         : 'savePost',
            'change #broadcast'       : 'toggleBroadcast',
            'change #published'       : 'togglePublished',
            'click input[name^=tag]'  : 'setTags',
            'click .show-posts'       : 'managePosts',
            'click .close-broadcast'  : 'closeBroadcast',
            'click #broadcast-btn'    : 'broadcast',
            'keyup #h1'               : 'populateAction',
            'keypress #search'        : 'searchAction',
            'click a.page'            : function(e) { appRouter.navigateAction(e); },
            'click .create-post'      : 'createPost',
            'click .news-item-delete' : function(e) { appRouter.newsDelete(e); }
        },
        initialize: function() {},
        setModel: function(model) {
            this.model = model;
            this.model.view = this;
            this.render();
        },
        newTag: function(e) {
            var tagName = this.$(e.target).val();
            if(e.keyCode == 13 && tagName != '') {
                if(appRouter.tags.exists(tagName)) {
                    $(e.target).val('');
                } else {
                    appRouter.tags.create({name: tagName, secureToken: $('.secureTokenTags').val()}, {
                        wait: true,
                        success: function(model, response) {
                            $(e.target).val('').blur();
                        },
                        error: function(model, response) {
                            showMessage(response.responseText, true);
                        }
                    })
                }
            }
        },
        searchAction: function(e) {
            if(e.keyCode == 13) {
                showSpinner();
                appRouter.posts.pager({reset:true});
            }
        },
        populateAction: function(e) {
            if(this.model.isNew()) {
                this.$('[data-destination=property]').val(e.currentTarget.value);
            }
        },
        selectTemplate: function(e) {
            $('#currentTemplateId').val(e.target.value);
        },
        toggleBroadcast: function() {
            this.model.set({
                'broadcast': this.$('#broadcast').prop('checked') ? 1 : 0
            });
        },
        toggleEvent: function() {
            if ($('#event').prop('checked')) {
                this.model.set({
                    'event': 1
                });
                $('#event-date').prop('hidden', false);
                $('#event-location').prop('hidden', false);
            } else {
                this.model.set({
                    'event': 0
                });
                $('#event-date').prop('hidden', true);
                $('#event-location').prop('hidden', true);
            }

        },
        togglePublished : function() {
            this.model.set({
                'published': this.$('#published').prop('checked') ? 1 : 0
            });
        },
        setTags : function() {
            var tags = [];
            _.each($('input[name^=tag]:checked'), function(e) {
                tags.push(appRouter.tags.get(e.value).toJSON());
            });
            this.model.set({tags : tags});
        },
        managePosts: function() {
            this.$('#manage-posts-container').show("slide", { direction: "right"});
            appRouter.posts.pager({reset: true});
        },
        closeManagePosts: function() {
            this.$('#manage-posts-container').hide("slide", { direction: "right"});
        },
        closeBroadcast: function() {
            this.$('#broadcast').prop('checked', false);
            this.$('#broadcast-list-container').hide('slide');
        },
        savePost: function() {
            showSpinner();
            var url = $('#url').val(),
                h1 = $('#h1').val(),
                teaserText = $('#teaser-text').val(),
                event = $('#event:checked').val(),
                eventDate = $('#event-date').val(),
                eventLocation = $('#event-location').val();
            this.model.set({
                title    : h1,
                teaser   : teaserText,
                secureToken  : $('.secureTokenNews').val(),
                createdAt    : $('#created-at').val(),
                event        : event,
                eventDate    : eventDate,
                eventLocation    : eventLocation,
                metaData : JSON.stringify({
                    h1           : h1,
                    title        : $('#title').val(),
                    navName      : $('#nav-name').val(),
                    url          : url,
                    oldUrl       : this.oldUrl,
                    teaserText   : teaserText,
                    metaKeywords : $('#meta-keywords').val(),
                    template     : $('#currentTemplateId').val(),
                    image        : $('#page-preview-image').attr('src'),
                    event        : event,
                    eventDate    : eventDate,
                    eventLocation : eventLocation
                })
            });

            if(!this.validatePost()) {
                hideSpinner();
                showMessage('You are missing required fields!', true);
                return false;
            }
            this.model.save(null, {success: function(model, response) {
                var newsMeta = JSON.parse(response.metaData);
                hideSpinner();
                appRouter.navigate('edit/' + model.id, true);
                parent.window.location.href = this.$('#website_url').val() + newsMeta.url;
            }});
        },
        broadcast: function() {
            var websites = [];
            _.each($('.broadcast-site:checked'), function(item) {
                websites.push($(item).data('wid'));
            });

            $.ajax({
                url: $('#website_url').val() + 'api/newslog/broadcast/',
                type: 'post',
                beforeSend: showSpinner(),
                data: {
                    nid: $('#broadcast-btn').data('nid'),
                    websites: websites
                },
                dataType: 'json'
            }).done(function(response) {
                hideSpinner();
                showMessage('Hooray! It looks like we did it!');
            });
        },
        validatePost: function() {
            var metaData = JSON.parse(this.model.get('metaData')),
                notRequiredFields = ['teaserText', 'metaKeywords', 'image', 'oldUrl', 'event', 'eventDate', 'eventLocation'],
                error = false;
            for(var property in metaData) {
                if(metaData.hasOwnProperty(property)) {
                    if($.inArray(property, notRequiredFields) != -1) {
                        continue;
                    }
                    var elId = property;
                    if(property == 'navName') {
                        elId = 'nav-name';
                    }
                    if(property == 'template') {
                        elId = 'templatelist';
                    }
                    if(!metaData[property]) {
                        this.$('#' + elId).addClass('error');
                        error = true || error;
                    } else {
                        this.$('#' + elId).removeClass('error');
                    }
                }
            }
            return !error;
        },
        render: function() {
            $( "#created-at" ).datepicker({dateFormat: 'M dd , yy'});
            $( "#event-date" ).datepicker({dateFormat: 'M dd , yy'});

            if (this.model.get('createdAt')) {
                $( "#created-at" ).val($.datepicker.formatDate('M dd , yy', new Date(Date.parse(this.model.get('createdAt').replace(/\-/g, '/')))));
            }
            if (this.model.get('event') === '1') {
                $( "#event" ).prop('checked', true);
                $('#event-date').prop('hidden', false);
                $('#event-location').prop('hidden', false);
                if (this.model.get('eventDate')) {
                    $( "#event-date" ).val($.datepicker.formatDate('M dd , yy', new Date(Date.parse(this.model.get('eventDate').replace(/\-/g, '/')))));
                }
                if (this.model.get('eventLocation')) {
                    $( "#event-location" ).val(this.model.get('eventLocation'));
                }
            }

            if (this.model.get('type') == 'external') {
                $('input, textarea').addClass('grayout').prop('disabled', true);
                $('#page-teaser-uploader-pickfiles, #save-btn, :checkbox').prop('disabled', true);
            }
            else {
                $('input, textarea').removeClass('grayout').prop('disabled', false);
                $('#page-teaser-uploader-pickfiles, #save-btn, :checkbox').prop('disabled', false);
            }

            if(!this.model.isNew()) {

                //set main values
                this.$('#title').val(this.model.get('title'));
                $('#published').prop('checked', (this.model.get('published') != '0'));

                //set other values
                var metaData = JSON.parse(this.model.get('metaData'));
                _.each(metaData, function(data, key) {
                    this.$('[name=' + key + ']').val(data);
                }, this);

                //set template
                if (metaData.template) {
                    $('#currentTemplateId').val(metaData.template);
                    $('option[value=' + metaData.template + ']').attr("selected", "selected");
                }

                //set an image
                if(typeof (metaData.image) != 'undefined' && metaData.image) {
                    this.$('#page-preview-image').attr('src', $('#website_url').val() + 'previews/' + metaData.image);
                }

                //populate tags
                this.$('#news-tags').find('input:checkbox:checked').prop('checked', false);
                _.each(this.model.get('tags'), function(tag) {
                    var el = appRouter.tags.get(tag.id).view.el;
                    $(el).find(':checkbox').prop('checked', true);
                });
            } else {
                $('#templatelist option').prop('selected', false);
            }
            this.oldUrl = this.$('#url').val();
            return this;
        },
        createPost: function() {
            this.$('#publish-later').hide();
            this.$('#published').prop('checked', true);
            this.$('input:text, textarea').val('').prop('disabled', false);
            this.$('#news-tags input:checkbox').prop('checked', false).prop('disabled', false);
            this.$('#page-preview-image').attr('src', $('#website_url').val()+'system/images/noimage.png');
        }
    });

    return appView;
});
