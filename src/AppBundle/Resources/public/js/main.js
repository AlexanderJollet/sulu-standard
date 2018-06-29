require.config({
    paths: {
        app: '../../app/js'
    }
});

define(function() {

    'use strict';

    return {

        name: "Messages Bundle",

        initialize: function(app) {

            app.components.addSource('app', '/bundles/app/js/components');

            // Cette méthode sert à afficher la liste des messages
            app.sandbox.mvc.routes.push({
                route: 'messages/list',
                callback: function() {
                    return '<div data-aura-component="app/list@app" data-aura-name="sulu" />';
                }
            });

            // Cette méthode sert à afficher un message
            app.sandbox.mvc.routes.push({
                route: 'messages/list/show::id',
                callback: function(id) {
                    return '<div data-aura-component="app/show@app" data-aura-id="' + id + '"/>';
                }
            });

            // Cette méthode sert à modifier un message
            app.sandbox.mvc.routes.push({
                route: 'messages/list/edit::id',
                callback: function(id) {
                    return '<div data-aura-component="app/form@app" data-aura-id="' + id + '"/>';
                }
            });
        }

    };
});
