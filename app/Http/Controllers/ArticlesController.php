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

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index($slug = null)
    {
        // 지연로드 104p
//        $articles = \App\Article::get();
//        $articles->load('user');

//        $articles = \App\Article::with('user')->get(); // 즉시 로드
        // with()는 엘로퀀드 모델 바로 다음에 위치, 인자는 테이블 이름이 아니라 모델에서 관계를 표현하는 메서드 이름
//        $articles = \App\Article::latest()->paginate(3);
//        dd(view('articles.index', compact('articles'))->render());

        // $slug 변수 값이 있을 때 없을 때의 쿼리를 분리
        $query = $slug ? \App\Tag::whereSlug($slug)->firstOrFail()->articles() : new \App\Article;
        $articles = $query->latest()->paginate(3);
        return view('articles.index', compact('articles')); // compact 는 변수와 그 값을 배열로 만들어줌
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 265p Null object 패턴
        // 글 작성과 수정 뷰가 폼을 공유하면서 old() 메서드의 두 번째 인자 때문에 널 포인터 오류가 발생
        // null 객체를 주입하는 것을 null object 패턴이라고 한다. 글 작성 폼에 더미 $article 객체를 바인딩해서 오류를 피하도록 했다.
        $article = new \App\Article;

        return view('articles.create', compact('article'));
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
        // ---- file upload 281p ----//
        if($request->hasFile('files')) { // hasFile() 메서드로 files 필드 확인
            $files = $request->file('files'); // file() 메서드는 폼 필드로 넘어온 배열 형태의 파일 목록 조회

            foreach($files as $file) {
                // 같은 파일을 두 번 업로듣할 수 있다. 파일 간의 이름 충돌을 피하기 위해 앞에 랜덤 문자를 붙였다.
                // FILTER_SANITIZE_URL 필터를 사용하면, URL로 접근할때 안전하지 않은 문자는 필터링할 수 있다.
                // UploadFile 인스턴스의 getClientOriginalName() 메서드는 임시 파일의 이름이 아니라 사용자가 업로드한 원래 파일 이름을 반환
                // getClientSize() 도 같은 맥락
                $filename = str_random().filter_var($file->getClientOriginalName(), FILTER_SANITIZE_URL);
                $file->move(attachments_path(), $filename); // 파일을 원하는 위치로 옮기는 구문

                $article->attachments()->create([
                    'filename' => $filename,
                    'bytes' => $file->getSize(),
                    'mime' => $file->getClientMimeType()
                    // 의사 결정이 필요한 부분
                    // 파일을 저장할 때 파일 크기와 마임 타입을 테이블에 미리 써놓을 것이냐 ?
                    // vs 모델을 조회할 때 파일시스템을 읽어서 크기와 마임 타입을 판단하고 반환할 것이냐?
                    // 쓰기는 한 번이지만 읽기는 여러 번 발생 -> 따라서 먼저 써 놓는 것이 좀 더 현명하다.
                ]);
            }
        }


        // -------
//        $article = \App\User::find(1)->articles()->create($request->all());

        // Illuminate\Http\Request $request 인스턴스는 로그인한 사용자 정보를 이미 가지고 있다.
        // 뿐만 아니라 auth 미들웨어는 로그인하지 않은 사용자가 이 메서드에 들어오는 것을 막아주므로 널 포인터 예외로부터 안전하다.

        $article = $request->user()->articles()->create($request->all());
        if(! $article) {
            $article->tags()->sync($request->input('tags')); // 폼 이름을 tags[]로 전송했으므로 $request->input('tags') 구문은 배열을 반환
            return back()->with('flash_message', '글이 저장되지 않습니다.')->withInput();
        }

//        var_dump('이벤트를 던집니다');
//        event(new \App\Events\ArticleCreated($article));
//        event('article.created', [$article]);
        // event() 함수는 이벤트를 방출한다. 1번째 인자는 이벤트이름, 2번째 인지는 이벤트 데이터
//        var_dump('이벤트를 던졌습니다');

        // 124p 실용적인 이벤트 시스템
        event(new \App\Events\ArticlesEvent($article));
        flash()->success('글이 저장되었습니다.');

        return redirect(route('articles.index'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function show($id)
    public function show(\App\Article $article)
    {
//         $article = \App\Article::findOrFail($id); // 라우트 모델 바인딩으로 인해 필요 없음 250p

//        debug($article->toArray());
        return view('articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(\App\Article $article)
    {
        // 입력 값 유효성 검사 할 때 validate() 를 썻던것과 비슷
        // authorize() 역시 부모 클래스가 가진 트레이트에서 찾을 수 있는데 이 메서드는 검사할 권한의 이름을 첫 번째 인자,
        // 검사할 모델 인스턴스를 두 번째 인자로 받는다.
        $this->authorize('update', $article);
        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\ArticlesRequest $request, \App\Article $article)
    {
        $article->update($request->all());
        $article->tags()->sync($request->input('tags'));
        flash()->success('수정하신 내용을 저장했습니다.');

        return redirect(route('articles.shohw', $article->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(\App\Article $article)
    {
        $this->authorize('delete', $article);
        $article->delete();

        return response()->json([], 204);
        // json() 메서드는 Content-type: application/json HTTP 응답 헤더를 붙이고
        // 첫 번째 인자로 받은 배열을 JSON 형식을 직렬화한다,
    }
}
