<div class="media">
    {{--아바타 조각 뷰를 범용적으로 사용하기 위해 $article 인스턴스 전체를 넘기지 않고 $user 로 바꾸어 넘겼다.--}}
    @include('users.partial.avatar', ['user'=>$article->user])
    <div class="media-body">
        <h4 class="media-heading">
            <a href="{{ route('articles.show', $article->id) }}">
                {{ $article->title }}
            </a>
        </h4>

        <p class="text-muted meta__article">
            <i class="fa fa-user"></i> {{ $article->user->name }}
            <i class="fa fa-clock-o"></i> {{ $article->created_at->diffForHumans() }}
        </p>

        @if ($viewName === 'articles.index')
            @include('tags.partial.list', ['tags' => $article->tags])
        @endif

    </div>
</div>