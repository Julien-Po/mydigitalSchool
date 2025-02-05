const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // Répertoire où seront stockés les fichiers compilés
    .setOutputPath('public/build/')
    // Chemin public utilisé par le serveur web
    .setPublicPath('/build')
    //.setManifestKeyPrefix('build/') // Pour CDN ou sous-dossier

    /*
     * ENTRY CONFIG
     * Chaque entry génère un fichier JS et un fichier CSS si nécessaire.
     */
    .addEntry('app', './assets/app.js')

    // Ajout de Sass (doit être avant `.enableSassLoader()`)
    .enableSassLoader()

    // Optimisations Webpack
    .splitEntryChunks()
    .enableSingleRuntimeChunk()

    // Nettoyage avant build
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    // Configuration Babel
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.38';
    })

    // Gestion des fichiers statiques (images, fonts, etc.)
    .copyFiles({
        from: './assets/images',
        to: 'images/[path][name].[ext]',
    })

    // Uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // Uncomment if you use React
    //.enableReactPreset()

    // Uncomment to enable script integrity (WebpackEncoreBundle 1.4+)
    //.enableIntegrityHashes(Encore.isProduction())

    // Uncomment if you have issues with jQuery plugins
    //.autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
