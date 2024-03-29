var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('web/build/')
    .setPublicPath('/build')

    .addEntry('app', './assets/js/app.js')
    .addEntry('base', './assets/js/base.js')
    .addEntry('index', './assets/js/index.js')
    .addEntry('profil', './assets/js/profil.js')
    .addEntry('statistiques', './assets/js/statistiques.js')
    .addEntry('buteurs', './assets/js/buteurs.js')

    .enableSingleRuntimeChunk()
    .enableSassLoader()

    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
;


module.exports = Encore.getWebpackConfig();