/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        'post-it-yellow': '#FFEB3B',
        'post-it-green': '#C8E6C9',
        'post-it-blue': '#BBDEFB',
        'post-it-pink': '#F8BBD0',
        'cork': '#8B7355',
      }
    },
  },
  plugins: [],
}

