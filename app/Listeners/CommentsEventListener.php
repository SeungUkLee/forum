<?php

namespace App\Listeners;

use App\Events\CommentsEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentsEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CommentsEvent  $event
     * @return void
     */
    public function handle(CommentsEvent $event)
    {
        $comment = $event->comment;
        $comment->load('commentable'); // 다음 로직이나 이메일 뷰에서 댓글에 연결된 아티클을 계속 사용하므로, 지연로드 이용(12장)
        $to = $this->recipients($comment);

        if(! $to) { //받는 사람이 없는 경우
            return;
        }

        \Mail::send('emails.comments.created', compact('comment'), function($message) use ($to) {
            $message->to($to);
            $message->subject(
                sprintf('[%s] 새로운 댓글이 등록되었습니다.', config('app.name'))
            );
        });
    }

    // 이벤트 데이터로 받은 댓글의 부모 댓글이 있으면 부모 댓글의 작성자 이메일을 찾는 로직
    private function recipients(\App\Comment $comment) {
        static $to = [];

        if($comment->parent) {
            $to[] = $comment->parent->user->email;

            $this->recipients($comment->parent);
        }

        if($comment->commentable->notification) {
            $to[] = $comment->commentable->user->email;
        }

        return array_unique($to); // 인자로 받은 배열에서 중복된 값을 제거
    }
}
