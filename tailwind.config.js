/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./vendor/livewire/flux-pro/stubs/**/*.blade.php",
    "./vendor/livewire/flux/stubs/**/*.blade.php",
  ],
  
  theme: {
    extend: {
      colors: {
        // Modo Claro - Granate + Arena
        'primario': {
          DEFAULT: '#8B1E2D',
          hover: '#721626',
          suave: '#F8E7E9',
        },
        'secundario': {
          DEFAULT: '#0EA5E9',
          hover: '#0284C7',
        },
        'fondo-app': '#FAFAF7',
        'superficie': '#FFFFFF',
        'borde': '#E7E2DA',
        'texto': {
          principal: '#191919',
          secundario: '#4B5563',
        },
        
        // Colores semánticos
        'exito': '#16A34A',
        'informacion': '#2563EB',
        'advertencia': '#D97706',
        'error': '#B91C1C',
      },
      fontFamily: {
        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui'],
      },
    },
  },
  
  plugins: [],
};