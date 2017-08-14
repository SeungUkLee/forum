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