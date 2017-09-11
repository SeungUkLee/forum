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