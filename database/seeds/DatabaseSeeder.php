<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    // 모델마다 artisan 시딩 명령을 수행하는 것은 매우 번거러움 -> 라라벨은 시더 클래스를 등록할 수 있는 마스터 시더 클래스를 제공
    // 필요한 이유 : 시딩의 순서때문(외래키와 연결한 다른 테이블의 열이 없으면 오류 발생)
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        if(config('database.default') !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }
        // truncate() : 테이블에 담긴 모든 데이터를 버린다. 기본키를 1부터 재배열한다
        App\User::truncate();
        $this->call(UsersTableSeeder::class); // $class:run() 메서드의 본문을 실행

        App\Article::truncate();
        $this->call(ArticlesTableSeeder::class);

        if(config('database.default') !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
}
