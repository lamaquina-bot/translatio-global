/** @type {import('tailwindcss').Config} */
export default {
  content: ['./src/**/*.{astro,html,js,jsx,md,mdx,svelte,ts,tsx,vue}'],
  theme: {
    extend: {
      colors: {
        primary: '#4A7C6F',
        secondary: '#D4A574',
        accent: '#2C5F8A',
        bg: '#FAFAF7',
      },
      fontFamily: {
        heading: ['Playfair Display', 'Georgia', 'serif'],
        body: ['Inter', '-apple-system', 'sans-serif'],
        chinese: ['Noto Sans SC', 'sans-serif'],
      },
    },
  },
  plugins: [],
};
