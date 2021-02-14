const colors = require('tailwindcss/colors');

// https://tailwindcss.com/docs/customizing-colors
module.exports = {
    purge: ["./../../../templates/**/*.twig"],
    theme: {
        extend: {
            colors: {
                // Build your palette here
                transparent: 'transparent',
                current: 'currentColor',
                warning: {
                    '': colors.amber,
                    DEFAULT: colors.amber["500"],
                    dark: colors.amber["900"]
                },
                info: {
                    DEFAULT: colors.blue["500"],
                    dark: colors.blue["900"],
                    light: colors.blue['300'],
                    extralight: colors.blue['100']
                },
                danger: {
                    DEFAULT: colors.red["500"],
                    dark: colors.red["900"],
                    light: colors.red['300'],
                    extralight: colors.red['100']
                },
                success: {
                    DEFAULT: colors.green["500"],
                    dark: colors.green["900"],
                    light: colors.green['300'],
                    extralight: colors.green['100']
                },
                primary: {
                    DEFAULT: colors.blue["700"],
                    dark: colors.blue["900"],
                    light: colors.blue['300'],
                    extralight: colors.blue['100']
                },
                secondary: {
                    DEFAULT: colors.gray["500"],
                    dark: colors.gray["900"],
                    light: colors.gray['300'],
                    extralight: colors.gray['100']
                }
            },
            backgroundColor: {
                warning: {
                    DEFAULT: colors.orange["400"]
                },
                info: {
                    DEFAULT: colors.blue["400"]
                },
                danger: {
                    DEFAULT: colors.red["500"]
                },
                success: {
                    DEFAULT: colors.green["500"]
                },
                primary: {
                    DEFAULT: colors.blue["700"]
                },
                secondary: {
                    DEFAULT: colors.gray["500"]
                }
            }
        }
    },
    variants: {},
    plugins: [],
    prefix: 'vw-',
};