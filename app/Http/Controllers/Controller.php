<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $cache;

    public function __construct() {
        $this->cache = app('cache');

        if((new \ReflectionClass($this))->implementsInterface(Cacheable::class) and taggable()) {
            $this->cache = app('cache')->tags($this->cacheTags());
        }
    }
    // 캐싱 적용을 모든 컨트롤러 메서드 마다 쓰는 것은 생산적이지 않으므로 부모 컨트롤러로 추출
    // 1 인자 : 캐시 키 , 2 인자 : 캐시유효시간, 3 인자 : 쿼리, 4 인자 : 쿼리 가장 끝에 붙는 get 등의 메서드이름, 5 인자 : $method의 인자들
    // ... is splat 연산자, $args is 배열
    protected function cache($key, $minutes, $query, $method, ...$args)
    {
        // taggable() 도우미 함수로 캐시 태그를 사용할 수 있는지 파악
        // 'cache'는 서비스 컨테이너에 등록된 클래스 별칭
        // ????를 받는게 문제
        $cache = taggable() ? app('cache')->tags('????') : app('cache');

        $args = (! empty($args)) ? implode(',', $args) : null;

        if(config('project.cache') === false) {
            return $query->{$method}($args); // $method 값을 메서드 이름으로 사용하기 위해 {}를 씀
        }

//        return \Cache::remember($key, $minutes, function () use($query, $method, $args) {
//            return $query->{$method}($args);
//        });

        return $cache->remember($key, $minutes, function () use($query, $method, $args) {
            return $query->{$method}($args);
        });
    }
}
