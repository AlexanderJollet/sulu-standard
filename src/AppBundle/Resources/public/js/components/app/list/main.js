define(['text!./list.html'], function(list) {

    var defaults = {
        templates: {
            list: list
        }
    };

    return {

        defaults: defaults,

        header: {
            title: 'messages.title',
            underline: false,

            toolbar: {
                buttons: {
                    show: {},
                    deleteSelected: {}
                }
            }
        },

        layout: {
            content: {
                width: 'max'
            }
        },

        initialize: function() {
            this.render();

            this.bindDomEvents();
            this.bindCustomEvents();
        },

        render: function() {
            this.$el.html(this.templates.list());

            this.sandbox.sulu.initListToolbarAndList.call(this,
                'messages',
                '/admin/api/messages/fields',
                {
                    el: this.$find('#list-toolbar-container'),
                    instanceName: 'messages',
                    template: this.sandbox.sulu.buttons.get({
                        settings: {
                            options: {
                                dropdownItems: [
                                    {
                                        type: 'columnOptions'
                                    }
                                ]
                            }
                        }
                    })
                },
                {
                    el: this.sandbox.dom.find('#messages-list'),
                    url: '/admin/api/messages',
                    searchInstanceName: 'messages',
                    searchFields: ['id', 'prenom', 'nom', 'email', 'message'],
                    resultKey: 'messages-items',
                    instanceName: 'messages',
                    actionCallback: this.toEdit.bind(this),
                    viewOptions: {
                        table: {
                            actionIconColumn: 'id'
                        }
                    }
                }
            );
        },

        toShow: function(id) {
            this.sandbox.emit('sulu.router.navigate', 'messages/list/show:' + id);
        },

        toEdit: function(id) {
            this.sandbox.emit('sulu.router.navigate', 'messages/list/edit:' + id);
        },

        deleteItems: function(ids) {
            for (var i = 0, length = ids.length; i < length; i++) {
                this.deleteItem(ids[i]);
            }
        },

        deleteItem: function(id) {
            this.sandbox.util.save('/admin/api/messages/' + id, 'DELETE').then(function() {
                this.sandbox.emit('husky.datagrid.messages.record.remove', id);
            }.bind(this));
        },

        bindDomEvents: function() {
        },

        bindCustomEvents: function() {
            this.sandbox.on('sulu.toolbar.add', function() {
                this.sandbox.emit('husky.datagrid.messages.items.get-selected', this.toShow.bind(this));
            }.bind(this));

            this.sandbox.on('husky.datagrid.messages.number.selections', function(number) {
                var deletedfix = number > 0 ? 'enable' : 'disable';
                var addfix = number === 1 ? 'enable' : 'disable';
                console.log(addfix, deletedfix);
                this.sandbox.emit('sulu.header.toolbar.item.' + deletedfix, 'deleteSelected', false);
                this.sandbox.emit('sulu.header.toolbar.item.' + addfix, 'add', false);
            }.bind(this));

            this.sandbox.on('sulu.toolbar.delete', function() {
                this.sandbox.emit('husky.datagrid.messages.items.get-selected', this.deleteItems.bind(this));
            }.bind(this));
        }
    };
});
