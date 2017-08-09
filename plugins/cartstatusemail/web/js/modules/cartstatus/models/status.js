define([
    'backbone'
], function (Backbone) {
    var StatusModel = Backbone.Model.extend({
        urlRoot: function(){
            return $('#website_url').val() + 'api/cartstatusemail/cartstatus/id/';
        }
    });

    return StatusModel;
});