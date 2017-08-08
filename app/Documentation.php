<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use File;

class Documentation
{
    // DB를 사용하지 않기 때문에 엘로퀀트를 상속하지 않는다.
    public function get($file = 'documentation.md') {
        $content = File::get($this->path($file));

        return $this->replaceLinks($content);
    }

    public function image($file) {
        return \Image::make($this->path($file, 'docs/images'));
        // \Image::make() 메서드는 Intervention\Image\Image 인스턴스를 반환
        // 이 인스턴스는 이미지를 조작하기 위한 기본 인스턴스
    }

    // 클라이언트 측 캐싱 189p
    public function etag($file) {
        $lastModified = File::lastModified($this->path($file, 'docs/images'));

        return md5($file . $lastModified);
    }

    protected function path($file, $dir = 'docs') {
        // 확장자가 없이 파일을 요청했을 경우 대비
        $file = ends_with($file, ['.md', '.png']) ? $file : $file . '.md';
        // $path의 절대 경로를 반환
        $path = base_path($dir . DIRECTORY_SEPARATOR . $file);
        // $dir 이미지 경로를 요청할때는 'docs/images' 인자를 추가로 넘긴다.

        if(! File::exists($path)) {
            abort(404, '요청하신 파일이 없습니다.');
        }
        return $path;
    }

    protected function replaceLinks($content) {
        return str_replace('/docs/{{version}}', '/docs', $content);
    }



}
