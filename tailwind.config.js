const defaultTheme = require('tailwindcss/defaultTheme')

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./assets/**/*.js', './templates/**/*.html.twig'],
  theme: {
    extend: {
      colors: {
        'nav-purple': '#475398',
        'button-color': '#556CBB',
        blue: {
          50: "#F3F5FB",
          100: "#E4EAF5",
          200: "#CEDAEF",
          300: "#ADC1E3",
          400: "#85A2D5",
          500: "#6885C9",
          600: "#556CBB",
          700: "#4A5CAB",
          800: "#475398",
          900: "#384270",
          950: "#262A45",
        }
      },
      gridTemplateColumns: {
        'quote-items': '3fr 0.5fr 0.5fr 1fr',
        'quote-list': '2fr 2fr 1fr',
      },
      fontFamily: {
        heading: ['"Orienta", sans-serif', ...defaultTheme.fontFamily.sans],
        sans: ['"Roboto Flex", sans-serif', ...defaultTheme.fontFamily.sans],
      },
    },
  },
  plugins: [require('@tailwindcss/forms'),],
}
