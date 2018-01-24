<?php

Route::get('/', 'ArticlesController@index');
Route::resource('articles', 'ArticlesController');

Route::get('docs/{file?}', 'DocsController@show');

// 현재 디렉터리를 기준으로 상대 경로를 사용, 이미지 URL은 /docs/images/foo-img-01.png다.
// 이 요청을 받는 라우트를 선언
Route::get('docs/images/{image}', 'DocsController@image')
    ->where('image', '[\pL-\pN\._-]+-img-[0-9]{2}.png');
// 정규표현식으로 {image} URL 파라미터의 모양을 한정.
// 한 글자 이상의 문자, 숫자, 점, 밑줄, 대시로 시작, -img- 다음에 두자리 숫자, .png 로 끝나는 문자열만 유효한 URL 파라미터로 받는다.

/* 사용자 가입 */
Route::get('auth/register', [
    'as' => 'users.create',
    'uses' => 'UsersController@create'
]);

Route::post('auth/register', [
    'as' => 'users.store',
    'uses' => 'UsersController@store'
]);

Route::get('auth/confirm/{code}', [
    'as' => 'users.confirm',
    'uses' => 'UsersController@confirm'
])->where('code', '[\pL-\pN]{60}'); //221p


/* 사용자 인증 */
Route::get('auth/login', [
    'as' => 'sessions.create',
    'uses' => 'SessionsController@create'
]);

Route::post('auth/login', [
    'as' => 'sessions.store',
    'uses' => 'SessionsController@store'
]);

Route::get('auth/logout', [
    'as' => 'sessions.destory',
    'uses' => 'SessionsController@destroy'
]);

/*비밀번호 초기화*/
Route::get('auth/remind', [
   'as' => 'remind.create',
    'uses' => 'PasswordsController@getRemind'
]);

Route::post('auth/remind',[
    'as' => 'remind.store',
    'uses' => 'PasswordsController@postRemind'
]);

Route::get('auth/reset/{token}', [
    'as' => 'reset.create',
    'uses' => 'PasswordsController@getReset'
])->where('token', '[\pL-\pN]{64}');

Route::post('auth/reset', [
    'as' => 'reset.store',
    'uses' => 'PasswordsController@postReset'
]);

// 태그에 속하는 글 목록 필터링 하기 위해.
// GET /tags/laravel/articles -> laravel 태그에 속하는 글
Route::get('tags/{slug}/articles', [
    'as' => 'tags.articles.index',
    'uses' => 'ArticlesController@index'
]);

// 파일 업로드를 비동기로 동작하기 때문에 글 저장 요청에 쓰던 POST /articles 경로를 계속 쓸 수는 없다
// 드롭존 라이브러리의 파일 업로드 요청을 받을 별도의 라우트를 만든다.
Route::resource('attachments', 'AttachmentsController', ['only' => ['store', 'destroy']]);

// 305p
Route::resource('comments', 'CommentsController', ['only' => ['update', 'destory']]);
Route::resource('articles.comments', 'CommentsController', ['only'=>'store']);
// Restful 리소스 컨트롤러를 만들때 (.)점을 이용하면 중첣 라우트를 만들수 있다.
// 이 라우팅은 /articles/{article}/comments URL을 만든다. 그리고 store() 메서드만 쓴다고 선언
// 중첩 라우팅을 쓰면 우리가 만든 모델 간 관계를 이용해서 App\Article::comments()->create()와 같이 댓글 모델을 쉽게 만들 수 있다.
// 댓글 수정이나 삭제할때는 Article 모델과의 관계를 굳이 이용할 필요가 없다. 그래서 /comments/{comment} 와 같은 URL을 만듬.

// 314p // UI 
// 314p // UI에서 전송한 사용자의 투포를 저장하는 메서드 하나만 필요 -> CommentController에서 쓰고 새로 컨트롤러 생성 x
Route::post('comments/{comment}/votes', [
    'as'=>'comments.vote',
    'uses' => 'CommentsController@vote'
]);

Route::get('test', function () {
    phpinfo();
});