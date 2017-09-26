@php
    $voted = null;
    if ($currentUser) {
        $voted = $comment->votes->contains('user_id', $currentUser->id)
            ? 'disabled="disabled"' : null;
    }
@endphp
{{--forelse로 반복하면서 댓글 컬렉션의 내용을 이 조각 뷰에 하나씩 렌더링하기 때문에 $voted 변수를 컨트롤러에서 받을 수 없다.--}}
{{--$currentUser->id가 로그인한 사용자 객체의 프로퍼티에 접근하기 때문에 조건문으로 사용자 로그인 테스트를 먼저 실시--}}
{{--조건문이 없으면 로그인하지 않은 사용자가 글 상세보기페이지를 열때 오류 발생--}}
{{--$voted 변수는 현재 로그인한 사용자가 이 댓글에 이미 투포했는지를 구분하는 플래그.  --}}
{{--변수에 담긴 값은 null 또는 HTML의 disabled 속성. --}}
{{--이미 투포했다면 <button ... disabled="disabled" >로 렌더링된 HTML을 내려주기 때문에 다시 투포를 할 수 없다.--}}


{{-- 최상위 댓글 하나는 여러개의 자식 댓글을 가진다. : 댓글 스레드라고 한다. --}}
{{-- 최상위 댓글, 자식이나 손자 등도 모두 이 조각 뷰를 사용--}}
{{-- {{isReply ...}} : 댓글 스레드 간에 구분을 위해 박스를 표시 할 때 사용할 css 클래스를 동적으로 생성하는 구문--}}
{{-- data-id : 자바스크립트로 댓글을 삭제할 때 유용, 서버에서 요청을 처리하고 글 상세 보기로 돌아올때 id 속성의 도움을 받아 방금 처리한 댓글로 이동--}}
<div class="media item__comment {{ $isReply ? 'sub' : 'top' }}" data-id="{{ $comment->id }}" id="comment_{{ $comment->id }}">
    @include('users.partial.avatar', ['user' => $comment->user, 'size' => 32])

    <div class="media-body">
        <h5 class="media-heading">
            <a href="{{ gravatar_profile_url($comment->user->email) }}">
                {{ $comment->user->name }}
            </a>
            <small>
                {{ $comment->created_at->diffForHumans() }}
            </small>
        </h5>

        <div class="content__comment">
            {!! markdown($comment->content) !!}
        </div>

        <div class="action__comment">
            @if($currentUser)
                <button class="btn__vote__comment" data-vote="up" title="좋아요" {{ $voted }}>
                    <i class="fa fa-chevron-up"></i> <span>{{ $comment->up_count }}</span>
                </button>
                <span> | </span>
                <button class="btn__vote__comment" data-vote="down" title="싫어요" {{ $voted }}>
                    <i class="fa fa-chevron-down"></i> <span>{{ $comment->down_count }}</span>
                </button>
            @endif
            {{-- 권한이 있는 사용자만 삭제 수정 버튼 볼 수 있다.--}}
            @can('update', $comment)
                <button class="btn__delete__comment">댓글 삭제 </button>
                <button class="btn__edit__comment"> 댓글 수정 </button>
            @endcan

            @if($currentUser)
                <button class="btn__reply__comment">답글 쓰기</button>
            @endif
        </div>

        @if($currentUser)
            {{-- 댓글 작성 폼에 $parentId 값을 넘김. 숨은 필드로 서버에 전송할 것이다 최상위 댓글을 전송할 때는 이 값이 없다.--}}
            @include('comments.partial.create', ['parentId' => $comment->id])
        @endif

        @can('update', $comment)
            @include('comments.partial.edit')
        @endcan

        @forelse($comment->replies as $reply)
            @include('comments.partial.comment', [
                'comment' => $reply,
                'isReply' => true,
            ])
        @empty
        @endforelse
    </div>
</div>