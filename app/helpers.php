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