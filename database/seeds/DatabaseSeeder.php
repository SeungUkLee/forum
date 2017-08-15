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


        // 268p
        /* 태그 */

        App\Tag::truncate();
        DB::table('article_tag')->truncate();
        $tags = config('project.tags');

        foreach ($tags as $slug => $name) {
            App\Tag::create([
                'name' => $name,
                'slug' => str_slug($slug)
            ]);
        }
        // $this->comman는 부모 클래스의 프로퍼티로서 \Illuminate\Console\Command 인스턴스다.
        // info() 메서드는 콘솔에 초록색 메시지를 출력해준다 (line(), ask() 등 다양한 메서드 존재)
        $this->command->info('Seeded: tags table');

        /* 변수 선언 */
        // 시더 클래스를 나누어 놓으면 오히려 중복이 더 발생하는 경우가 있다.
        // 그렇기 때문에 이렇게 따로 시딩 로직에서 쓰기 위한 변수들을 선언
        $faker = app(Faker\Generator::class); // 모델 팩토리에 사용했던 가짜 데이터를 만드는데 사용 (app() 도우미 함수로 $faker 인스턴스 생성)
        $users = App\User::all();
        $articles = App\Article::all();
        $tags = App\Tag::all();

        /* 아티클 및 태그 연결 */
        foreach ($articles as $article) {
            $article->tags()->sync(
                $faker->randomElements( // randomElements(array $array, int $count=1) 메서드는 $array 배열에서 $count 로 지정한 만큼의 원소를 랜덤하게 반환
                    $tags->pluck('id')->toArray(), // 태그 아이디로만 구성된 배열 반환
                    rand(1, 3) // 1~3 랜덤 정수 반환
                )
            );
        }

        $this->command->info('Seeded: article_tag table');

        if(config('database.default') !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

    }
}
