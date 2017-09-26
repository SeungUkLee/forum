<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(\App\Http\Requests\CommentsRequest $request, \App\Article $article) {
        $comment = $article->comments()->create(array_merge(
            $request->all(),
            ['user_id' => $request->user()->id]
        ));
        // 사용자로부터 받은 폼 데이터와 사용자 아이디를 합친다. 생성자에서 auth 미들웨어를 사용한다고 했으므로 사용자 객체에 접근 가능.
        // 라라벨이 $request 인스턴스에 사용자를 이미 주입해 놓았으므로 $request->user()->id 처럼 쓸 수 있다.
        // array_merge : 인자로 받은 배열을 합친다. 배엘에 같은 키가 있으면 나중에 받은 배열의 키 값을 사용

        flash()->success('작성하신 댓글을 저장했습니다.');

        return redirect(route('articles.show', $article->id).'#comment_'.$comment->id);
        // /articles/{articles}#comment={comments} 를 반환 (3장 라우팅에서 배운 URL 조각)
        // 개별 댓글 뷰(28.2.3절)에서 댓글마다 HTMl id 속성을 부여. URL 조각이 있으면 페이지를 로드한 후 해당 아디로 화면을
        // 자동 스크롤 한다. 즉, 작성한 댓글을 보여준다.
    }

    public function update(\App\Http\Requests\CommentsRequest $request, \App\Comment $comment) {
        $comment->update($request->all());

        return redirect(route('articles.show', $comment->commentable()->id).'#comment_'.$comment->id);
    }

    public function destroy(\App\Comment $comment) {
        $comment->delete();

        return response()->json([], 204);
    }

    public function vote(Request $request, \App\Comment $comment) {
        $this->validate($request, [
           'vote' => 'required|in:up,down',
        ]);

        if($comment->votes()->whereUserId($request->user()->id)->exists()) {
            return response()->json(['error'=>'already_voted'], 409);
        }

        $up = $request->input('vote') == 'up' ? true : false;

        $comment->votes()->create([
            'user_id' => $request->user()->id,
            'up' => $up,
            'down' => !  $up,
            'voted_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);

        return response()->json([
            'voted' => $request->input('vote'),
            'value' => $comment->votes()->sum($request->input('vote')),
        ]);
    }
}
