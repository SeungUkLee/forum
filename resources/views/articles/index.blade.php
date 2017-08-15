@extends('layouts.app')

@section('content')
    @php $viewName = 'articles.index'; @endphp

    <div class="page-header">
        <h4>포럼<small> / 글 목록</small></h4>
    </div>

    <div class="text-right">
        <a href="{{ route('articles.create') }}" class="btn btn-primary">
            <i class="fa fa-plus-circle"></i> 새 글 쓰기
        </a>
    </div>


    <div class="row">
        <div class="col-md-3">
            <aside>
                @include('tags.partial.index')
            </aside>
        </div>
        <div class="col-md-9">
            <article>
                @forelse($articles as $article)
                    @include('articles.partial.article', compact('article'))
                @empty
                    <p class="text-center text-danger">글이 없습니다.</p>
                @endforelse
            </article>

            @if($articles->count()) {
            <div class="text-center">
                {!! $articles->appends(Request::except('page'))->render() !!}
                {{--appends 메서드 체인--}}
                {{--?page2&foo=bar 처럼 여러개의 쿼리 스트링이 있을 때, 페이지를 이동해도 page를 제외한 나머지 쿼리 스트링은 계속 지키기 위해 사용--}}
            </div>
            @endif
        </div>
    </div>


@stop
