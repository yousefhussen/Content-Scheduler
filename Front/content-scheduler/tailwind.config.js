/** @type {import('tailwindcss').Config} */
module.exports = {
  variants: {
    extend: {
      translate: ['checked'],
      backgroundColor: ['checked'],
    },
    
  },
  mode: 'jit',
  content: ["./src/**/*.{html,ts}"],
  theme: {
    extend: {
      colors: {
        magenta: {
          400: '#D946EF',
          500: '#B91C83',
          600: '#6B246B',
        },
      }
    },
  },
  plugins: [],
};
