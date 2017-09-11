const elixir = require('laravel-elixir');

require('laravel-elixir-vue');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application as well as publishing vendor resources.
 |
 */

// scss 파일을 css로 바꾸고, 세 개의 자바스크립트 파일을 하나로 만든 후, 파일 이름에 버전을 매겨서 최종 파일 두 개로 떨구는 파일 스크립트다.
// 추가적으로 폰트 파일을 정해진 위치에 배포
elixir((mix) => {
    mix.sass('app.scss')
    mix.webpack('app.js');

    mix.scripts([
        '../../../node_modules/highlightjs/highlight.pack.js',
        '../../../public/js/app.js',
        '../../../node_modules/select2/dist/js/select2.js',
        '../../../node_modules/dropzone/dist/dropzone.js'
    ], 'public/js/app.js');

    mix.version([
        'css/app.css',
        'js/app.js'
    ]);


    mix.copy('node_modules/font-awesome/fonts', 'public/build/fonts');
    // mix.browserSync({proxy:'localhost:8000'});


});
