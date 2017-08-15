@extends('layouts.app')

@section('content')
    @php $viewName = 'articles.show'; @endphp


    <div class="page-header">
        <h4>포럼<small> / {{ $article->title }}</small></h4>
    </div>

    <article data-id="{{ $article->id }}">
        @include('articles.partial.article', compact('article'))

        <p> {!! markdown($article->content) !!}</p>

        @include('tags.partial.list', ['tags'=>$article->tags])
    </article>

    <div class="text-center action__article">
        @can('update', $article)
            <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-info">
                <i class="fa fa-pencil"></i> 글 수정
            </a>
        @endcan

        @can('delete', $article)
            <button class="btn btn-danger button__delete">
                <i class="fa fa-trash-o"></i> 글 삭제
            </button>
        @endcan

        <a href="{{ route('articles.index') }}" class="btn btn-default">
            <i class="fa fa-list"></i> 글 목록
        </a>
    </div>

@stop

@section('script')
    <script>
//        마스터 레이아웃을 만들 때 HTML 헤더 영역에 CSRF 토큰을 넣어 두었는데 그 값을 읽어서 모든 ajax 요청 헤더에 붙이는 구문
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]'.attr('content'))
            }
        });

        $('.button__delete').on('click', function(e) {
            var articleId = $('article').data('id');

            if(confirm('글을 삭제합니다.')) {
                $.ajax({
                    // 제이쿼리는 모든 HTTP 메서드를 쓸 수 있으므로 메서드 오버라이드 필요 x
                    type:'DELETE',
                    url:"/articles/"+ articleId
                }).then(function() {
                    window.location.href = '/articles/';
                });
            }
        });

    </script>
@stop