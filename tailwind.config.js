const defaultTheme = require('tailwindcss/defaultTheme')

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./assets/**/*.js', './templates/**/*.html.twig'],
  theme: {
    extend: {
      colors: {
        'nav-purple': '#475398',
        'button-color': '#556CBB',
      },
      gridTemplateColumns: {
        subgrid: 'subgrid',
        'quote-items': '3fr 0.5fr 0.5fr 1fr',
      },
      fontFamily: {
        heading: ['"Orienta", sans-serif', ...defaultTheme.fontFamily.sans],
        sans: ['"Roboto Flex", sans-serif', ...defaultTheme.fontFamily.sans],
      },
    },
  },
  plugins: [],
}
