const mix = require('laravel-mix');

mix.js('resources/js/signup.js', 'public/js')
   .styles('resources/css/app.css', 'public/css/app.css');
