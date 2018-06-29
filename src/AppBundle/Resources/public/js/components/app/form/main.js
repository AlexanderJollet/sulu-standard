define(['underscore', 'jquery', 'text!./form.html'], function(_, $, form) {

    return {

        defaults: {
            templates: {
                form: form,
                url: '/admin/api/messages/<%= id %>'
            }
            // translations: {
            //     title: 'public.title',
            //     content: 'news.content'
           // }
        },

        header: {
            title: 'Messages.headline',
            toolbar: {
                buttons: {
                    save: {
                        parent: 'saveWithOptions'
                    }
                }
            }
        },

        layout: {
            content: {
                width: 'fixed',
                leftSpace: true,
                rightSpace: true
            }
        },

        initialize: function() {
            this.render();

            this.bindDomEvents();
            this.bindCustomEvents();
        },

        render: function() {
            this.$el.html(this.templates.form({translations: this.translations}));

            this.form = this.sandbox.form.create('#messages-form');
            this.form.initialized.then(function() {
                this.sandbox.form.setData('#messages-form', this.data || {});
            }.bind(this));
        },

        bindDomEvents: function() {
            this.$el.find('input, textarea').on('keypress', function() {
                this.sandbox.emit('sulu.header.toolbar.item.enable', 'save');
            }.bind(this));
        },

        bindCustomEvents: function() {
            this.sandbox.on('sulu.toolbar.save', this.save.bind(this));
            this.sandbox.on('sulu.header.back', function() {
                this.sandbox.emit('sulu.router.navigate', 'messages/list');
            }.bind(this));
        },

        save: function(action) {
            if (!this.sandbox.form.validate('#messages-form')) {
                return;
            }

            var data = this.sandbox.form.getData('#messages-form'),
                url = this.templates.url({id: this.options.id});

            this.sandbox.util.save(url, 'PUT', data).then(function(response) {
                this.afterSave(response, action);
            }.bind(this));
        },

        afterSave: function(response, action) {
            this.sandbox.emit('sulu.header.toolbar.item.disable', 'save');

            if (action === 'back') {
                this.sandbox.emit('sulu.router.navigate', 'messages/list');
            } else if (!this.options.id) {
                this.sandbox.emit('sulu.router.navigate', 'messages/list/edit:' + response.id);
            }
        },

        loadComponentData: function() {
            var promise = $.Deferred();

            if (!this.options.id) {
                promise.resolve();

                return promise;
            }
            this.sandbox.util.load(_.template(this.defaults.templates.url, {id: this.options.id})).done(function(data) {
                promise.resolve(data);
            });

            return promise;
        }
    };
});
