<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 295p
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index(); // user 모델과 일대다 관계를 담기 위한 열
            // 댓글의 대슬을 위한 재귀적 일대다 관계를 표현 , 이 열에 값이 없으면 최상위 댓글
            // 외래 키 제약 사항을 연결하는 구문에서 onDelete() 메서드를 쓰지 않는 것을 눈여겨 볼 것
            $table->integer('parent_id')->unsigned()->nullable();
            $table->string('commentable_type'); // 모델 이름을 담는다 (App\Aritcle 문자열이 담길 것이다.)
            $table->integer('commentable_id')->unsigned(); // 모델의 기본 키를 담는다. 엘로퀀트는 위의 열과 같이 조합해서 연결된 모델을 찾을수 있다
            $table->text('content');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('parent_id')->references('id')->on('comments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function(Blueprint $table) {
            // 일대다 관계는 외래 키를 선언했는데, 역의 마이그레이션을 정의할 때 외래 키 제약이 걸려 있으먄 롤백할 때 문제가
            // 생길 수 있으므로 외래 키를 먼저 제거하는 것이 좋은 습관!, 다형적 관계는 외래키로 연결 할 수 없다(표현 할 수 없다.)
            $table->dropForeign('comments_parent_id_foreign');
            $table->dropForeign('comments_user_id_foreign');
        });

        Schema::dropIfExists('comments');
    }
}
