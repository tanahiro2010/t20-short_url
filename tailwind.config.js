/** @type {import('tailwindcss').Config} */

module.exports = {
  content: [
    "./src/**/*.{html,js}",
    "./src/*.html"
  ],
  theme: {
    extend: {
      fontFamily: {
        momomanic: ['Monomaniac One', 'sans-serif'],
        sawarabi: ['Sawarabi Gothic', 'sans-serif']
      }
    },
  },
  plugins: [],
}