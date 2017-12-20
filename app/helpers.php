<?php
/**
 * Created by PhpStorm.
 * User: SeungUk
 * Date: 2017. 8. 8.
 * Time: 오전 1:38
 */

// 우리가 만든 함수는 전역 네임스페이스를 사용 -> 다른 함수와 충돌을 피하기 위해 function_exits() 사용
if(! function_exists('markdown')) {
    function markdown($text = null) {
        return app(ParsedownExtra::class)->text($text);
    }
}

if(! function_exists('gravatar_url')) {
    function gravatar_url($email, $size = 48) {
        return sprintf("//www.gravatar.com/avatar/%s?s=%s", md5($email), $size);
        // http또는 https 를 붙이지 않았다.-> https를 사용할때 http로 시작하는 리소스를 로드하면 보안 경고가 발생.
        // 이를 방지하기 위해 // 만 썻다.
        // 이와 같은 도우미 함수 대신 접근자를 사용해도 무방.(254p)
    }
}

if(! function_exists('gravatar_profile_url')) {
    function gravatar_profile_url($email) {
        return sprintf("//www.gravatar.com/%s", md5($email));
    }
}

if(! function_exists('attachments_path')) {
    function attachments_path($path= '') {
        return public_path('files'.($path ? DIRECTORY_SEPARATOR.$path : $path));
        // public_path 는 프로젝트의 웹서버 루트 디렉토리 절대 경로를 반환하는 함수
    }
}

if(! function_exists('format_filesize')) {
    function format_filesize($bytes) {
        // 인자로 받은 값을 1024로 나누어 0.9보다 크면 나누기를 계속 반복
        // 반복과정에서 얻은 $step 값을 $suffix 배열의 인덱스로 사용
        // 사람이 읽이 편한 파일 크기 문자열을 얻을 수 있다.
        if(! is_numeric($bytes)) return 'NaN';

        $decr = 1024;
        $step = 0;
        $suffix = ['bytes', 'KB', 'MB'];

        while(($bytes/$decr) > 0.9) {
            $bytes = $bytes / $decr;
            $step ++;
        }

        return round($bytes, 2) . $suffix[$step];
    }
}

if(! function_exists('link_for_sort')) {
    // 첫번째 인자는 정렬 기준이 되는 테이블의 열 이름.
    // 두번째 인자는 링크 텍스트로 표시할 문자열, 세번째 인자는 링크 태그에 더 추가할 속성값
    function link_for_sort($column, $text, $params = []) {
        $direction = request()->input('order');
        $reverse = ($direction == 'asc') ? 'desc' : 'asc';

        // 현재 표시된 페이지의 sort 쿼리 스트링과 이 함수로 넘어온 $column과 비교하여 같은지 검사 (즉, 현재 정렬 기준인 셈이다.)
        // 이때는 $text 인자의 값에 정렬 방향을 표현하는 아이콘을 붙인다. 오름차순일때는 A->Z 내림차순 Z->A
        if(request()->input('sort') == $column) {
            $text = sprintf("%s %s",
                $direction == 'asc'
                    ? '<i class="fa fa-sort-alpha-asc"></i>'
                    : '<i class="fa fa-sort-alpha-desc"></i>',
                $text
            );
        };

        // http_build_query() PHP함수는 인자로 받은 연관 배열을 쿼리스트링으로 바꾼다.
        // ex) ['foo'=>'bar', 'baz'=>'qux'] 일 경우 foo=bar&baz=qux가 나온다.
        $queryString = http_build_query(array_merge(
            request()->except(['sort', 'order']), // 인자로 받은 필드를 제거
            ['sort' => $column, 'order' => $reverse], // 현재 정렬 방향의 반대 방향을 담은 쿼리 스트링을 만든다. 즉 클릭할때마다 글 목록의 정렬 방향이 토글
            $params
        ));

        return sprintf(
            '<a href="%s?%s">%s</a>',
            urldecode(request()->url()), // 쿼리스트링은 우리가 만들어서 붙일 예정이므로 기존의 쿼리 스트링을 버리고 URL 경로까지만 사용.
            $queryString,
            $text
        );
    }
}

if(! function_exists('cache_key')) {
    function cache_key($base)
    {
        // 쿼리 결과는 각 요청마다 다르다. 캐시키는 이 요청들을 서로 구분해야 해서 getQueryString 사용
        $key = ($uri = request()->getQueryString())
            ? $base.'.'.urlencode($uri)
            : $base;

        return md5($key);
    }
}

// 캐시 태그가 가능하지 판단하는 도우미 함수
if(! function_exists('taggable')) {
    function taggable()
    {
        return in_array(config('project.default'), ['memcached', 'redis'], true);
    }
}