/** @type {import('tailwindcss').Config} */
export default {
  // Bagian 'content' ini SANGAT PENTING.
  // Ini memberitahu Tailwind untuk men-scan semua file ini
  // dan men-generate CSS yang dibutuhkan.
  content: [
    './resources/**/*.blade.php', // Ini akan mencakup semua file Blade kamu
    './resources/**/*.js',
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
  ],

  theme: {
    extend: {
      // Ini dari file app.css lama kamu
      fontFamily: {
        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui'],
      },
      
      // Ini dari file profile.blade.php kamu
      colors: {
        'custom-blue': {
          DEFAULT: '#4A90E2', // Untuk bg-custom-blue, text-custom-blue
          '10': 'rgba(74, 144, 226, 0.1)', // Untuk bg-custom-blue-10
          '20': 'rgba(74, 144, 226, 0.2)', // Untuk hover:bg-custom-blue-20
        }
      },
    },
  },

  plugins: [
    // Jika kamu nanti butuh plugin form, bisa ditambahkan di sini
    // require('@tailwindcss/forms'),
  ],
}