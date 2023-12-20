/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        'nav-purple': '#475398',
        'button-color': '#556CBB'
      },
      fontSize: {
        "small": '10px',
      }
    },minHeight: {
      '250': '250px' 
    }
  },
  plugins: [],
}