const defaultTheme = require('tailwindcss/defaultTheme')

/** @type {import('tailwindcss').Config} */
module.exports = {
    // darkMode: 'selector',
    content: ['./assets/**/*.js', './templates/**/*.html.twig'],
    theme: {
        colors: {
            transparent: 'transparent',
            black: '#14110F',
            white: '#ffffff',
            blue: {
                lightest: "#E0E8F9",
                lighter: "#CDDFFB",
                light: '#A4CCFD',
                DEFAULT: '#3754CB',
                dark: '#1B254B',
                darker: '#191B23',
                darkest: '#0C0D12',
            },
            grey: {
                lightest: '#F3F0F8',
                lighter: '#E5E5E5',
                light: '#D9D9D9',
                DEFAULT: '#888888',
                dark: '#545454',
                darker: '#333333',
            },
            red: {
                50: '#fff1f3',
                100: '#ffe0e4',
                200: '#ffc7ce',
                300: '#ffa0ac',
                400: '#ff697c',
                500: '#f93a52',
                600: '#e71d36',
                700: '#c21329',
                800: '#a01425',
                900: '#851725',
                950: '#49060e',
            },
            orange: {
                50: '#fff8eb',
                100: '#ffecc6',
                200: '#ffd688',
                300: '#ffbb4a',
                400: '#ff9f1c',
                500: '#f97c07',
                600: '#dd5702',
                700: '#b73a06',
                800: '#942b0c',
                900: '#7a240d',
                950: '#461002',
            },
            yellow: {
                50: '#fcf8ea',
                100: '#f9f0c8',
                200: '#f4df94',
                300: '#edc657',
                400: '#e6af2e',
                500: '#d6971c',
                600: '#b87416',
                700: '#935315',
                800: '#7a4219',
                900: '#68371b',
                950: '#3d1c0b',
            },
            green: {
                50: '#effef6',
                100: '#d9ffec',
                200: '#b5fdda',
                300: '#7bfabe',
                400: '#3aee9a',
                500: '#11d67a',
                600: '#07b263',
                700: '#09814a',
                800: '#0e6d42',
                900: '#0d5a39',
                950: '#01321d',
            },
            primary: '#3754CB',
            secondary: '#E5E5E5',
            success: '#09814a',
            warning: '#FF9900',
            error: '#e71d36',
            info: '#259DD0',
        },
        extend: {
            backgroundColor: ({theme}) => ({
                light: theme('colors.grey.lightest'),
                dark: theme('colors.blue.darkest'),
                hover: {
                    light: theme('colors.light'),
                    dark: `color-mix(in srgb, ${theme(
                        'colors.dark'
                    )} 92%, rgba(255, 255, 255))`,
                },
            }),
            fontFamily: {
                heading: ['"Orienta", sans-serif', ...defaultTheme.fontFamily.sans],
                sans: ['"Roboto Flex", sans-serif', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                sm: '0.888rem', // 1.125 ** -1
                base: '1rem', // 1.125 ** 0
                lg: '1.125rem', // 1.125 ** 1
                xl: '1.27rem', // 1.125 ** 2
                '2xl': '1.42rem', // 1.125 ** 3
                '3xl': '1.60rem', // 1.125 ** 4
                '4xl': '1.80rem', // 1.125 ** 5
                '5xl': '2.03rem', // 1.125 ** 6
            },
            flexBasis: {
                'fit-content': 'fit-content',
            },
            minHeight: {
                250: '250px',
            },

        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('tailwindcss-animate')
    ],
}
