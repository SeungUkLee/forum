{{--요청 메서드는 GET : 서버의 응답을 받고 새로운 페이지가 로드되어도 쿼리 스트링은 그대로 남아있다.--}}
{{--쿼리 스트링의 필드 이름은 q 이며 요청할 URL은 /articles--}}
<form method="get" action="{{ route('articles.index') }}" role="search">
    <input type="text" name="q" class="form-control" placeholder="포럼 검색">
</form>