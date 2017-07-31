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

//Route::get('/', function () {
//    return view('welcome');
//});


// 라우트 이름
// 컨트롤러 로직에서 다른 라우트로 리디렉션하거나
// 뷰에서 다른 라우트로 이동하는 링크를 만들때 이점이 있어 편리하다

//Route::get('/', [
//    'as' => 'home',
//    function () {
//        return '제 이름은 "home" 입니다.';
//    }
//]);

//Route::get('/home', function() {
//    return redirect(route('home'));
//});
// redirect() 함수는 도우미함수 리디렉션 HTTP 응답을 반환한다


// with() 메서드를 체인하여 데이터를 바인딩
//Route::get('/', function() {
//    return view('welcome')->with([
//        'name' => 'Foo',
//        'greeting' => '안녕하세요>',
//    ]);
//});

// view() 의 두번째인자로 넘기는 방법
//Route::get('/', function() {
//    return view('welcome', [
//        'name' => 'Foo',
//        'greeting' => '안녕하세요>',
//    ]);
//});

//---
//60p
Route::get('/', 'WelcomeController@index');
Route::resource('articles', 'ArticlesController');

//74p
//Route::get('auth/login', function() {
//    $credentials = [
//        'email' => 'john@example.com',
//        'password' => 'password'
//    ];
//
//    if(! auth()->attempt($credentials)) {
//        return '로그인 정보가 정확하지가 않습니다.';
//    }
//
//    return redirect('protected');
//});

//Route::get('protected', ['middleware'=>'auth', function() {
//    dump(session()->all());
//
////    if(! auth()->check()) {
////        return '누구세요?';
////    }
//
//    return '어서 오세요'. auth()->user()->name;
//}]);
//
//Route::get('auth/logout',function() {
//    auth()->login();
//
//    return '또 오세요~';
//});
Auth::routes();

Route::get('/home', 'HomeController@index');
//DB::listen(function ($query) {
//    var_dump($query->sql);
//});

//119p~120p
// Event 파서드에 listen() 메서드로 이벤트 수신, 두번째 인수는 처리 로직인데 콜백으로 처리하였다. 콜백 안에서 수신한 이벤트 데이터에 접근할 수 있다.
// 이벤트 리스너는 라라벨 부트스트랩 과정에서 컴퓨터 메모리에 적재되어 수신 대기 상태였다가 관심 있는 이벤트가 나타나는 순간 동작
//Event::listen('article.created', function($article) {
//    var_dump('이벤트를 받았습니다. 받은 데이터(상태)는 다음과 같습니다.');
//    var_dump($article->toArray());
//});
// 라우팅 정의 파일에 이벤트 처리 로직을 모두 쓸 수 없다.-> app/Providers/EventServiceProvider.php 에 작성하자.

//146p
Route::get('mail', function() {
    $article = App\Article::with('user')->find(1);

    return Mail::send( // 클러저에서 보내는 $message변수는 뷰에 사용가능. 두번째 인자로 뷰에 바인딩할 데이터를 넘길때 $message변수를 사용하지 않도록 주의
        ['text'=>'emails.articles.created-text'],
        compact('article'),
        function($message) use ($article) {
//            $message->from('lsy931106@gmail.com');
            $message->to('dltmddnr5@naver.com');
            $message->subject('새 글이 등록되었습니다-'.$article->title);

        }
    );
});