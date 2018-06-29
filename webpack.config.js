// webpack.config.js
var Encore = require('@symfony/webpack-encore');
const CopyWebpackPlugin = require('copy-webpack-plugin')

Encore
    .setOutputPath('web/build/')
    .setPublicPath('/build')

    .addEntry('app', './assets/js/main.js')
    .addStyleEntry('global', './assets/css/index.scss')
    .enableSassLoader()
    .addPlugin(new CopyWebpackPlugin([
            { from: 'assets/image/', to: 'images' }
        ]
    ))
    //.enableSourceMaps(!Encore.isProduction())
    //.enableVersioning()
;

module.exports = Encore.getWebpackConfig();
