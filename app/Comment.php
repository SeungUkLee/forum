<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes; // SoftDeletes 트레이트를 사용하겠다고 선언
    // 모델의 delete() 메서드는 테이블에서 레코드를 삭제하지 않고 deleted_at 필드에 삭제된 시각만 기록 (완전히 삭제하려면 forceDelete())

    protected $dates = ['deleted_at'];
    protected $fillable = ['commentable_type', 'commentable_id', 'user_id', 'parent_id', 'content'];
    protected $with = ['user','votes'];
    protected $appends = ['up_count', 'down_count'];
    // Comment::up_count 와 Comment::down_count 는 모델에 없던 속성
    // 서버 측 코드에서 App\Comment::find(1)->up_count 와 같이 속성값을 쉽게 조회 가능
    // 그런데 App\Comment::find(1)->toArray() 또는 toJson() 으로 출력할 때는 원래 모델에 없던 속성값은 출력되지 않는다.
    // 이 때 엘로퀀트 $appends 프로퍼티를 이용 할 수 있다.

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function commentable() {
        return $this->morphTo(); // morphTo() 메서드로 다형적 관계 연결
        // App\Comment::find(1)->commentable 문법을 이용하여 연결된 모델 조회 가능
        // morphTo() 메서드가 테이블의 commentable_type, commentable_id 열을 소비하는 것
    }

    public function replies() { // 댓글끼리 재귀적인 일대다 관계를 표현
        // 만약 현재 댓글 인스턴스에 여러 개의 답글이 있는 상황, 둘 사이의 관계를 연결하는 참조하는 키는 parent_id 다.
        return $this->hasMany(Comment::class, 'parent_id')->latest();
    }

    public function parent() { // replies() 메서드의 반대 관계를 표현
        return $this->belongsTo(Comment::class, 'parent_id', 'id');
    }
    
    public function votes() {
        return $this->hasMany(Vote::class);
    }

    public function getUpCountAttribute() {
        return (int) $this->votes()->sum('up');
        // sum()은 컬렉션에서 쓸 수 있는 메서드
    }

    public function getDownCountAttribute() {
        return (int) $this->votes()->sum('down');
    }
}
