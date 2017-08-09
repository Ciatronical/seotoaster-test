define([
    'underscore',
    'backbone'
], function(_, Backbone){

    var TagView = Backbone.View.extend({
        tagName: 'li',
        className: 'tag-widget',
        template: $('#tagTemplate').template(),
        nameInput: null,
        events: {
            "click .ticon-close": "kill"
        },
        initialize: function(){
            this.model.bind('change', this.render, this);
            this.model.view = this;
        },
        render: function(){
            $(this.el).html($.tmpl(this.template, this.model.toJSON()));
            this.nameInput = this.$el.children('.tag-name');
            return this;
        },
        kill: function(){
            var confirmMsg = $('#confirm-msg').text().replace('%tag%', this.model.get('name'));
            var modelHolder = this.model;

            showConfirm(confirmMsg, function(){
                modelHolder.destroy({success: function(model, response) {
                    model.view.remove();
                }});
            });
        }

    });
    return TagView;
});