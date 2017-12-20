<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsOnArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->boolean('notification')->default(1); // 댓글이 달렸을 때 글 작성자에게 이메일 알림을 보낼지 말지 결정하기 위한 플래그
            $table->tinyInteger('view_count')->default(0); // 글의 조회수

            if(config('database.default') == 'mysql') {
                DB::statement('ALTER TABLE articles ADD FULLTEXT search(title, content)');
                // 전체 텍스트 검색을 위한 DB 조작 구문, 우리는 articles.title 과 articles.content 열에서 전체 텍스트 검색을 할 것이다.
                // 이 구문은 MYSQL에서만 사용 가능..
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function(Blueprint $table) {
            $table->dropColumn(['notification', 'view_count']);
        });
    }
}
