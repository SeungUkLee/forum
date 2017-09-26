<div class="media media__create__comment {{ isset($parentId) ? 'sub' : 'top'}}">
    @include('users.partial.avatar', ['user'=>$currentUser, 'size' => 32])

    <div class="media-body">
        {{--304p route('articles.comments.store', $article->id) 중첩라우트 사용 Article 모델과의 관계를 이용하여 Comment 모델을 만들기 위해서--}}
        <form method="POST" action="{{route('articles.comments.store', $article->id)}}" class="form-horizontal">
            {!! csrf_field() !!}

            @if(isset($parentId))
                <input type="hidden" name="parent_id" value="{{$parentId}}">
            @endif

            <div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
                <textarea name="content" class="form-control">{{old('content')}}</textarea>
                {!! $errors->first('content', '<span class="form-error">:message</span>') !!}
            </div>

            <button type="submit" class="btn btn-primary btn-sm">
                전송하기
            </button>
        </form>
    </div>
</div>