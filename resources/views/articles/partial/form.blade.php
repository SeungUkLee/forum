<div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
    <label for="title"> 제목</label>
    <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}" class="form-control"/>
    {!! $errors->first('title', '<span class="form-error">:message</span>') !!}
</div>

{{--수정할 때는 빈 폼이 아니라 기존 값들을 채운 뷰를 출력해야 한다.--}}
{{--old() 함수는 첫 번째 인자로 받은 키가 세션에 없으면, 두 번째 인자로 받은 기본값을 출력--}}
{{--두 번째 인자는 각 필드에 해당하는 모델의 프로퍼티 값--}}
{{--즉, 수정폼이 처음 로드 되면 현재 모델에 저장된 값을 출력하고,--}}
{{--유효성 검사 오류가 나서 이 폼으로 다시 돌아오면 사용자의 이전 입력값이 출력--}}
<div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
    <label for="content"> 본문 </label>
    <textarea name="content" id="content" rows="10" class="form-control">{{ old('content', $article->content) }}</textarea>
    {!! $errors->first('content', '<span class="form-error">:message</span>') !!}

    {{--마크다운 컴파일 결과 미리보기--}}
    {{--<div class="preview__content">--}}
        {{--{!! markdown(old('content', '...')) !!}--}}
    {{--</div>--}}
</div>