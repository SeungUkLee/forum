<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'content', 'notification', 'view_count']; // 대량 할당 허용

    // 265p
    // 즉시로드 : 컨트롤러에서 with() 메서드 체인 말고 다른 방법
    // 메서드 대신 엘로퀀트의 프로퍼티를 이용한 것, 이 방법은 남용하지 말고 필요할 때만 사용
    // 이 프로젝트에서 Article 모델은 항상 사용자 정보가 필요하기 때문에 이 방법 사용
    protected $with = ['user'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }

    public function attachments() {
        return $this->hasMany(Attachment::class);
    }

    public function comments() {
        // comments 테이블엔 일대다 또는 일대일 관계처럼 article_id 열이 없다. 다형적 관계에서는 hasMany() 대신 morphMany() 메서드 이용
        return $this->morphMany(Comment::class, 'commentable');
    }

    /* accessor */
    public function getCommentCountAttribute() {
        // 댓글 수 출력
        return (int) $this->comments()->count();
    }
}
