define(['underscore', 'jquery', 'text!./message.html'], function(_, $, message) {

    var defaults = {
        templates: {
            message: message,
            url: '/admin/api/messages/<%= id %>'
        }
    };

    return {

        defaults: defaults,

        header: {
            title: 'messages.show',
            underline: false,
        },

        initialize: function () {
            this.render(this.data);
            this.bindCustomEvents()
        },

        render: function (data) {
            this.$el.html(this.templates.message({data: data}));
        },

        loadComponentData: function () {

            var promise = $.Deferred();

            this.sandbox.util.load(_.template(this.defaults.templates.url, {id: this.options.id}))
                .done(function(data) {
                promise.resolve(data);
            })

            return promise;

        },
        bindCustomEvents: function() {
            this.sandbox.on('sulu.header.back', function() {
                this.sandbox.emit('sulu.router.navigate', 'messages/list');
            }.bind(this));
        },
}
});
