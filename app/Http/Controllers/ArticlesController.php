<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 지연로드 104p
//        $articles = \App\Article::get();
//        $articles->load('user');

//        $articles = \App\Article::with('user')->get(); // 즉시 로드
        // with()는 엘로퀀드 모델 바로 다음에 위치, 인자는 테이블 이름이 아니라 모델에서 관계를 표현하는 메서드 이름
        $articles = \App\Article::latest()->paginate(3);
//        dd(view('articles.index', compact('articles'))->render());
        return view('articles.index', compact('articles')); // compact 는 변수와 그 값을 배열로 만들어줌
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
//    public function store(Request $request)
//    {
//        $rules = [
//            'title' => ['required'],
//            'content' => ['required', 'min:10'],
//        ];
//
//        $messages = [
//            'title.required' => '제목은 필수 입력 항목입니다.',
//            'content.required' => '본문은 필수 입력 항목입니다.',
//            'content.min' => '본문은 최소 :min 글자 이상이 필요합니다.',
//        ];
//
//
////        $this->validate($request, $rules, $messages); // 13.2 트레이트 메서드 이용 114p
//        $validator = \Validator::make($request->all(), $rules, $messages); // 13.1 유효성 검사 기본
//
//        if($validator->fails()) {
//            return back()->withErrors($validator)->withInput();
//        }
//
//        $article = \App\User::find(1)->articles()->create($request->all());
//
//        if(! $article) {
//            return back()->with('flash_message', '글이 저장되지 않습니다.')->withInput();
//        }
//
//        return redirect(route('articles.index'))->with('flash_message', '작성하신 글이 저장되었습니다.');
//    }
    // 13.3 폼 리퀘스트 클래스 이용했을 경유 store 메서드
    public function store(\App\Http\Requests\ArticlesRequest $request) {
        $article = \App\User::find(1)->articles()->create($request->all());
        if(! $article) {
            return back()->with('flash_message', '글이 저장되지 않습니다.')->withInput();
        }

//        var_dump('이벤트를 던집니다');
//        event(new \App\Events\ArticleCreated($article));
//        event('article.created', [$article]);
        // event() 함수는 이벤트를 방출한다. 1번째 인자는 이벤트이름, 2번째 인지는 이벤트 데이터
//        var_dump('이벤트를 던졌습니다');

        // 124p 실용적인 이벤트 시스템
        event(new \App\Events\ArticlesEvent($article));
        return redirect(route('articles.index'))->with('flash_message', '작성하신 글이 저장되었습니다.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = \App\Article::findOrFail($id);
        debug($article->toArray());
        return view('articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return __METHOD__. '은(는) 다음 기본키를 가진 Article 모델을 수정하기 위한 폼을 담은 뷰를 반환합니다'. $id;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return __METHOD__. '은(는) 사용자의 입력한 폼 데이터로 다음 기본 키를 가진 Article 모델을 수정합니다';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return __METHOD__. '은(는) 다음 기본키를 가진 Article 모델을 삭제합니다.';
    }
}
