<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            // unsigned() : 양의 정수 -> 외래키는 양의 정수 제약조건으로 선언하는게 좋다
            $table->integer('user_id')->unsigned()->index();
            $table->string('title');
            $table->text('content');

            $table->timestamps();

            // foreign: 테이블끼리 외래 키 관계를 연결
            // articles.user_id 열은 users.id 열을 참조한다는 의미.
            // onUpdate('cascade'), onDelete('cascade') 는 users.id 열이 변경되거나 삭제될때의 동작옵션을 정의
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
