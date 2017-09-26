<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    public $timestamps = false; //votes.created_at 과 votes.updated_at 열을 제외
    // 마이그레이션에서 $table->timestamps() 메서드를 쓰지 않으면 $timestamps = false 로 프로퍼티 값을 줘야한다.
    // 반면에 의미론적으로 더 적합한 voted_at 열을 추가 -> 이값을 Carbon\Carbon 인스턴스로 쓰기 위해 $dates 프로퍼티 값에 넣었다.

    protected $fillable = ['user_id', 'comment_id', 'up', 'down', 'voted_at'];
    protected $dates = ['voted_at'];
    
    /* relationship */
    public function comment() {
        return $this->belongsTo(Comment::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }


    /* mutator (변경자)-접근자의 반대 */
    // DB에 저장할 때 데이터의 형식을 바꾼다. 메서드 이름을 set_파스칼_표기법의_프로퍼티_이름_Attribute($value)로 지어야한다.
    // 메서드 본문에서는 
    public function setUpAttribute($value) {
        $this->attributes['up'] = $value ? 1 : null;
    }

    public function setDownAttribute($value) {
        $this->attributes['down'] = $value ? 1 : null;
    }


}
