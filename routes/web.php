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


// with() 메서드를 체인하여 데이터를 바인딩
Route::get('/', function() {
    return view('welcome')->with([
        'name' => 'Foo',
        'greeting' => '안녕하세요>',
    ]);
});

// view() 의 두번째인자로 넘기는 방법
Route::get('/', function() {
    return view('welcome', [
        'name' => 'Foo',
        'greeting' => '안녕하세요>',
    ]);
});