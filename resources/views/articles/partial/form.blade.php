<div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
    <label for="title"> 제목</label>
    <input type="text" name="title" id="title" value="{{ old('title', $article->title) }}" class="form-control"/>
    {!! $errors->first('title', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{$errors->has('tags') ? 'has-error' : ''}}">
    <label for="tags">태그</label>
    {{--글과 태그는 다대다 관계이므로 multiple 속성을 이용해 다중 선택할 수 있도록 하였다.--}}
    <select class="form-control" name="tags[]" id="tags" multiple="multiple">
        @foreach($allTags as $tag)
            {{--$article->tags->contains($tag->id)는 수정 폼을 열었을 떄, 기존에 선택했던 값을 표시하기 위한 문장--}}
            {{--contains() 는 php의 in_array()를 한번 더 감싼 메서드 $article->tags 컬렉션에 $tag->id 에 해당하는 키가 있는지 검사--}}
            {{--현재 아티클에 태그 1번이 저장되어 있다면 <option value="1" 와 같ㅇ티 렌더링 된다.--}}
            <option value="{{ $tag->id }}" {{ $article->tags->contains($tag->id) ? 'selected="selected"' : '' }}>
                {{ $tag->name }}
            </option>
        @endforeach
    </select>
    {!! $errors->first('tags', '<span class="form-error>:message</span>') !!}
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

@section('script')
    {{-- 부모 뷰의 스크립트 섹션을 덮어쓰기 않기 위해 @parent 키워드 사용--}}
    @parent
    <script>
        $("#tags").select2({
            placeholder:'태그를 선택하세요(최대 3개)',
            maximumSelectionLength:3
        });
    </script>
@stop