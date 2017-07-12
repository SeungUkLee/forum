<?php

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = App\User::all();

        // databases/factories/ModelFactory 를 보면 user_id 정의가 없다
        // 사용자의 관계를 이용해서 포럼 글을 만든다, $users 변수에 사용자 컬렉션을 담고 컬렉션을 순회면서 포럼 글을 만든다
        // each() 대신 foreach()를 써도 무방
        $users->each(function ($user) {
            // make() 메서드는 새로운 모델 인스턴스를 반환 db에는 저장 x
            // save() 메서드는 create()와 같은 일을 하는데 받을 수 있는 타입(객체와 배열)만 다르다.
            $user->articles()->save(
                factory(App\Article::class)->make()
            );
        });
    }
}
