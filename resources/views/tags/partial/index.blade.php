<p class="lead"><i class="fa fa-tags"></i>태그</p>

<ul class="list-unstyled">
    @foreach($allTags as $tag)
        {{--str_contains() 함수는 문장에서 키워드를 칮는다. 있으면 true 반환--}}
        {{--request->path() 는 현재 요청의 url 경로를 반환--}}
        <li {!! str_contains(request()->path(), $tag->slug) ? 'class="active"' : ''!!}>
            <a href="{{ route('tags.articles.index', $tag->slug) }}">
                {{ $tag->name }}
                {{-- count()는 컬렉션 개수를 반환하는 메소드--}}
                @if($count = $tag->articles->count())
                    <span class="badge badge-default">{{ $count }}</span>
                @endif
            </a>
        </li>
    @endforeach
</ul>