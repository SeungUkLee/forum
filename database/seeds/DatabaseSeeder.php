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


        /* 27.3 이미지 업로드 291p */
        App\Attachment::truncate();
        if(! File::isDirectory(attachments_path())) {
            File::makeDirectory(attachments_path(), 775, true);
        }
        File::cleanDirectory(attachments_path());


        // public/files/.gitignore 파일이 있어야 커밋할 때 빈 디렉터리를 유지할 수 있다.
        File::put(attachments_path('.gitignore'), "*\n!.gitignore");
        $this->command->error( // 시간이 오래 걸리는 작업을 시각적으로 표현하기 위해 실제 동작과 다르지만 error 메서드를 이용하여 메세지 출력
            'Downloading ' . $articles->count() . ' images from lorempixel. It takes time...'
        );
        $articles->each(function ($article) use ($faker) {
            $path = $faker->image(attachments_path());
            $filename = File::basename($path);
            $bytes = File::size($path);
            $mime = File::mimeType($path);
            $this->command->warn("File saved: {$filename}");
            $article->attachments()->save(
                factory(App\Attachment::class)->make(compact('filename', 'bytes', 'mime'))
            );
        });

        $this->command->info('Seeded: attachments table and files');

        /* 최상위 댓글 */
        $article->each(function ($article) {
            $article->comments()->save(factory(App\Comment::class)->make());
            $article->comments()->save(factory(App\Comment::class)->make());
        });
        /* 자식 댓글 */
        $articles->each(function ($article) use ($faker) {
            $commentIds = App\Comment::pluck('id')->toArray();
            // 반복문을 쉰회할 때마다 자식 댓글이 생성
            foreach (range(1, 5) as $index) {
                $article->comments()->save(
                    factory(App\Comment::class)->make([
                        'parent_id' => $faker->randomElement($commentIds),
                    ])
                );
            }
        });

        $this->command->info('Seeded: comments table');


        $comments = App\Comment::all();

        $comments->each(function ($comment) {
            $comment->votes()->save(factory(App\Vote::class)->make());
            $comment->votes()->save(factory(App\Vote::class)->make());
            $comment->votes()->save(factory(App\Vote::class)->make());
        });

        $this->command->info('Seeded: votes table');

        if(config('database.default') !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

    }
}
