{{-- 컨트롤러로부터 받아야 하는 데이터들 => 306p에서 처리--}}
{{--@php--}}
    {{--$currentUser = auth()->user();--}}
    {{--$comments = $article->comments;--}}
{{--@endphp--}}

<div class="page-header">
    <h4> 댓글 </h4>
</div>

<div class="form__new__comment">
    @if($currentUser)
        @include('comments.partial.create')
    @else
        @include('comments.partial.login')
    @endif
</div>

<div class="list__comment">
    @forelse($comments as $comment)
         {{--comments.partial.comment : 댓글 본문, 댓글 수정 폼, 댓글의 답글 작성폼, 댓글 수정/삭제--}}
        @include('comments.partial.comment', [
            'parentId' => $comment->id,
            'isReply' => false,
            ])
         {{--isReply는 최상위 댓글과 자식 댓글을 구분하는 UI 조작을 위한 것.--}}
    @empty
    @endforelse
</div>

@section('script')
    @parent
    <script>
        $('.btn__delete__comment').on('click', function(e) {
            var commentId = $(this).closest('.item__comment').data('id'),
                    articleId = $('article').data('id');

            if(confirm('댓글을 삭제합니다.')) {
                $.ajax({
                    type: 'POST',
                    url: "/comments/" + commentId,
                    data: {
                        _method: "DELETE"
                    }
                }).then(function() {
                    $('#comment_'+commentId).fadeOut(1000, function() { $(this).remove(); });
                });
            }
        });
        $('.btn__vote__comment').on('click', function (e) {
            var self = $(this)
            var commentId = self.closest('.item__comment').data('id');

            // 투표 저장 요청 vote=up 또는 vote=down 이다.
            $.ajax({
                type:'POST',
                url: '/comments/'+commentId +'/votes',
                data : {
                    vote:self.data('vote')
                }
            }).then(function(data) { // 성공 콜백함수에서는 현재 댓글의 투표 버튼을 비활성화하고 서버에서 받은 투표 집계 값으로 기존 숫자를 업데이트
                self.find('span').html(data.value).fadeIn();
                self.attr('disabled', 'disabled');
                self.siblings().attr('disabled', 'disabled');
            });
        });
    </script>
@stop