const postcssImport = require('postcss-import');
const tailwindcss = require('tailwindcss');
const postcssNested = require('postcss-nested');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const cssNanoAdvOpts = {

}

// https://github.com/anibalsanchez/XT-TailwindCSS-Starter
module.exports = {
    plugins: [
        postcssImport,
        tailwindcss,
        postcssNested,
        autoprefixer,
        ...(process.env.NODE_ENV === "production" ? [
            cssnano({preset: ['advanced', cssNanoAdvOpts]})
        ] : [])

    ],
};