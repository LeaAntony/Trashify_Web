/** @type {import('tailwindcss').Config} */
export default {
    content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    'node_modules/preline/dist/*.js',
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
  ],
  theme: {
    extend: {
                  colors: {
                    "Genoa" : "#368666",
                    "Aquamarine" : "#B5FFCE",
                    "Orange-Peel" : "#FF9900",
                    "Shalimar" : "FFF3AE",
                    "Flamingo" : "F15950",
                    "Beauty-Bush" : "EEB3B0",
                    "Brick-Red" : "#B5333D",
                    "Medium-Carmine" : "#B6333C",
                    
            },
        fontFamily: {
                poppins: ["Poppins", "mono"],
            },
    },
  },
  plugins: [
      require('preline/plugin'),
      require('@tailwindcss/forms')
  ],
}

