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
        
        return view('articles.index', compact('articles')); // compact 는 변수와 그 값을 배열로 만들어줌
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return __METHOD__. '은(는) Ariticle 컬렉션을 만들기 위한 폼을 담은 뷰를 반환합니다.';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return __METHOD__. '은(는) 사용자의 입력한 폼 데이터로 새로운 Article 컬렉션을 반환합니다';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return __METHOD__. '은(는) 다음 기본 키를 가진 Ariticle 모델을 조회합니다.'. $id;
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