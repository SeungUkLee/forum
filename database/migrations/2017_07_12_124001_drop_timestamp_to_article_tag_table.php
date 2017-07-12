<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTimestampToArticleTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // articl_tag table을 생성할때 실수로 timestamp 를 지우지않고 생성하여서...
    public function up()
    {
        Schema::table('article_tag', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
