/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './app/**/*.php',
    './resources/**/*.{php,blade.php,js,ts,jsx,tsx}',
    './vendor/roots/acorn/**/*.php',
  ],

  theme: {
    extend: {
      colors: {
        border: 'hsl(var(--border))',
        input: 'hsl(var(--input))',
        ring: 'hsl(var(--ring))',
        background: 'hsl(var(--background))',
        foreground: 'hsl(var(--foreground))',
        primary: {
          DEFAULT: 'hsl(var(--primary))',
          foreground: 'hsl(var(--primary-foreground))',
        },
        secondary: {
          DEFAULT: 'hsl(var(--secondary))',
          foreground: 'hsl(var(--secondary-foreground))',
        },
        destructive: {
          DEFAULT: 'hsl(var(--destructive))',
          foreground: 'hsl(var(--destructive-foreground))',
        },
        muted: {
          DEFAULT: 'hsl(var(--muted))',
          foreground: 'hsl(var(--muted-foreground))',
        },
        accent: {
          DEFAULT: 'hsl(var(--accent))',
          foreground: 'hsl(var(--accent-foreground))',
        },
        card: {
          DEFAULT: 'hsl(var(--card))',
          foreground: 'hsl(var(--card-foreground))',
        },

        white: '#ffffff',
        black: '#000000',
        accent: '#00EEFF',
        siniy: '#35467e;',
      },

      fontFamily: {
        inter: ['Inter', 'sans-serif'],
        roboto: ['Roboto', 'sans-serif'],
        poppins: ['Poppins', 'sans-serif'],
      },

      borderRadius: {
        lg: 'var(--radius)',
        md: 'calc(var(--radius) - 0.125rem)',
        sm: 'calc(var(--radius) - 0.25rem)',
      },

      // Анимация аккордеона на чистом CSS (без Radix/JS)
      keyframes: {
        'accordion-down': {
          from: { gridTemplateRows: '0fr' },
          to: { gridTemplateRows: '1fr' },
        },
        'accordion-up': {
          from: { gridTemplateRows: '1fr' },
          to: { gridTemplateRows: '0fr' },
        },
      },
      animation: {
        'accordion-down': 'accordion-down 0.2s ease-out',
        'accordion-up': 'accordion-up 0.2s ease-out',
      },
    },
  },
  plugins: [
    require('tailwind-scrollbar'),
    // другие плагины...
  ],
};
