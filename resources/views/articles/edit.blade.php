@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h4>포럼 <small>/글 수정/ {{ $article->title }}</small></h4>
    </div>

    <form action="{{ route('articles.update', $article->id) }}" method="POST">
        {!! csrf_field() !!}
        {!! method_field('PUT') !!}
        {{--method_field() 도우미함수는 HTTP 메서드 오버라이드를 위한 숨은 필드를 출력한다--}}

        @include('articles.partial.form')

        <div class="form-group">
            <button type="submit" class="btn btn-primary"> 수정하기 </button>
        </div>
    </form>
@stop