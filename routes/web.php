<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// 라우트 이름
// 컨트롤러 로직에서 다른 라우트로 리디렉션하거나
// 뷰에서 다른 라우트로 이동하는 링크를 만들때 이점이 있어 편리하다

Route::get('/', [
    'as' => 'home',
    function () {
        return '제 이름은 "home" 입니다.';
    }
]);

Route::get('/home', function() {
    return redirect(route('home'));
});
// redirect() 함수는 도우미함수 리디렉션 HTTP 응답을 반환한다


