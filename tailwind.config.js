const defaultTheme = require('tailwindcss/defaultTheme')

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./assets/**/*.js', './templates/**/*.html.twig', './safelist.txt'],
  theme: {
    extend: {
      colors: {
        black: '#05060a',
        white: '#ffffff',
        blue: {
          50: '#f3f5fb',
          100: '#e4eaf5',
          200: '#cedaef',
          300: '#adc1e3',
          400: '#85a2d5',
          500: '#6885c9',
          600: '#556cbb',
          700: '#4a5cab',
          800: '#475398',
          900: '#384270',
          950: '#262a45',
        },
        grey: {
          50: '#f8f8f8',
          100: '#f0f0f0',
          200: '#dcdcdc',
          300: '#bdbdbd',
          400: '#989898',
          500: '#7c7c7c',
          600: '#656565',
          700: '#525252',
          800: '#464646',
          900: '#3d3d3d',
          950: '#292929',
        },
        gray: {
          50: '#f8f8f8',
          100: '#f0f0f0',
          200: '#dcdcdc',
          300: '#bdbdbd',
          400: '#989898',
          500: '#7c7c7c',
          600: '#656565',
          700: '#525252',
          800: '#464646',
          900: '#3d3d3d',
          950: '#292929',
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
      },
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
  plugins: [require('@tailwindcss/forms'), require('tailwindcss-animate')],
}
