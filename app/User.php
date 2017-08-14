<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $dates = ['last_login'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'confirm_code', 'activated'
    ]; // 대량 할당 허용

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'confirm_code'
    ];

    // SQLite 는 TINYINT 값이 문장열로 저장되는데
    // 엘로퀀드에서 읽을 떄 string 으로 반환하기 때문에 비교연산에서 예상치 못한 결과가 발생 할 수도 있다.
    // 따라서 조회할 때 항상 boolean 타입으로 반환하도록 해야한다. 219p
    protected $casts = [
        'activated' => 'boolean'
    ]; // 모델에서 프로퍼티 값을 조회할때 자동으로 타입 변환 해준다.

    public function articles() {
        return $this->hasMany(Article::class);
    }

    /* helpers */
    public function isAdmin() {
        return ($this->id === 1) ? true : false;
    }
}
