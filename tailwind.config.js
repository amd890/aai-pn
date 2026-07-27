import defaultTheme from 'tailwindcss/defaultTheme';
import colors from 'tailwindcss/colors';

/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/Livewire/**/*.php",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', ...defaultTheme.fontFamily.sans],
        heading: ['Outfit', ...defaultTheme.fontFamily.sans],
      },
      colors: {
        // Overriding amber to match AAI Logo Orange (#CF7315)
        amber: {
          50: '#fdf6ef',
          100: '#fae9d7',
          200: '#f4d2b2',
          300: '#edb484',
          400: '#e58e51',
          500: '#cf7315', // AAI Orange
          600: '#bf5d11',
          700: '#9f4611',
          800: '#813915',
          900: '#683014',
          950: '#381608',
        },
        // Overriding slate to have a subtle tint of AAI Logo Deep Blue (#0A137B)
        slate: {
          50: '#f4f6fb',
          100: '#e6ebf5',
          200: '#ccd8eb',
          300: '#a3badd',
          400: '#7596c9',
          500: '#5277b3',
          600: '#3e5d95',
          700: '#324a7a',
          800: '#2c3f66',
          900: '#273555',
          950: '#070c30', // Deep Navy Blue derived from AAI Blue
        },
        aai: {
          blue: '#0A137B',
          orange: '#CF7315',
        }
      },
      boxShadow: {
        'glow-gold': '0 0 25px -5px rgba(207, 115, 21, 0.4)',
        'glow-blue': '0 0 25px -5px rgba(10, 19, 123, 0.4)',
      },
      backgroundImage: {
        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
      }
    }
  },
  plugins: [],
}
