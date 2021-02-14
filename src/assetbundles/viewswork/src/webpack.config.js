// https://medium.com/better-programming/tailwindcss-and-symfonys-webpack-encore-7bfc8c18665b

const Encore = require('@symfony/webpack-encore')
Encore
    .setOutputPath('../build')
    .setPublicPath('')
    //.addStyleEntry('tailwind', './css/tailwind.css')

    .addEntry('app', './js/app.js')
    .enableSassLoader()
    // enable post css loader
    .enablePostCssLoader((options) => {
        options.postcssOptions = {
            // the directory where the postcss.config.js file is stored
            config: './postcss.config.js',
        }
    })
    .enableSingleRuntimeChunk()
    // define the environment variables
    .configureDefinePlugin(options => {
        options['process.env'].SELECTOR_PREFIX = "'vw-'";
    })
;
module.exports = Encore.getWebpackConfig();