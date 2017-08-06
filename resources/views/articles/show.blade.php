@extends('layouts.app')

@section('content')
    @php $viewName = 'articles.show'; @endphp

    <div class="page-header">
        <h4>
            <a href="{{ route('articles.index') }}">
                {{ trans('forum.title') }}
            </a>
            <small>
                / {{ $article->title }}
            </small>
        </h4>
    </div>


@stop
