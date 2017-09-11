<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = ['filename', 'bytes', 'mime'];

    public function article() {
        return $this->belongsTo(Article::class);
    }

    /* 접근자  284p*/
    public function getBytesAttribute($value) { // 이 모델이 원래 가지고 있던 속성값을 사람이 이해하기 쉬운 bytes, KB, 단위로 바꿈
        return format_filesize($value);
    }

    public function getUrlAttribute() {
        return url('files/'.$this->filename); // 뷰에서 링크를 편리하기 만들기 위해 추가
    }
}
