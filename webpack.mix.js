const mix = require('laravel-mix');

mix.setPublicPath('dist')
   .babel('src/resources/js/app.js', 'dist/app.js')  // no vue  // .js('src/resources/js/app.js', '.')
   .sass('src/resources/sass/app.scss', '.');

if (mix.inProduction()) {
    mix.disableNotifications();
    mix.version();
}
else {
    mix.sourceMaps();
}
