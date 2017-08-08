<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocsController extends Controller
{
    protected $docs;

    public function __construct(\App\Documentation $docs)
    {
        $this->docs = $docs;
    }

    public function show($file = null) {
//        $index = markdown($this->docs->get());
//        $content = markdown($this->docs->get($file ?: 'installation.md'));

        // 캐싱 182p
        // 캐시 적재를 위해 Cache 파서드와 remember() 메서드 이용
        // remember() 첫번쨰 인자 캐시 키, 두번째 인자 캐시유효기간(분), 세번째인자 클로저
        $index = \Cache::remember('docs.index', 120, function() {

            return markdown($this->docs->get());
        });

        // use 키워드 : 클로저에 $file변수를 바인딩 시키는 문법
        $content = \Cache::remember("docs.{$file}", 120, function() use ($file) {
//            dd('reachead'); // 캐시 동작 테스트 php artisan cache:clear 와 함께 테스트
            return markdown($this->docs->get($file ?: 'installation.md'));
        });

        return view('docs.show', compact('index', 'content'));
    }

    public function image($file) {
        $reqEtag = \Request::getEtags(); // Request 파서드.
        // 현재 HTTP 요청에 관한 모든 정보를 담고 있는 인스턴스와 같다고 생각 (Illuminate\Http\Request)
        // getEtags() 메서드는 If-Non-Match 값을 반환(반환값은 배열)

        $genEtag = $this->docs->etag($file);

        // 클라이언트의 HTTP 요청에 달려온 If-Non-Match 값과 우리가 만든 Etag 값을 비교하여 같으면 304 응답
        if(isset($reqEtag[0])) {
            if($reqEtag[0] === $genEtag) {
                return response('', 304);
            }
        }

        $image = $this->docs->image($file);

        return response($image->encode('png'), 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=0',
            'Etag'=> $genEtag
        ]); // $image 는 Intervention\Image\Image 인스턴스다 이 인스턴스는 encode() 메서드를 이용하여 다른 포맷의 이미지를 바꿀수 있다.
    }


}
