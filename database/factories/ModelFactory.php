<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
//        'password' => $password ?: $password = bcrypt('secret'),
        //모든 사용자의 비밀번호를 기억할 수 있는 문자열로 통일 , 여러 사용자의 아이디로 로그인하여 테스트하기 위함
        'password' => bcrypt('password'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Article::class, function(Faker\Generator $faker) {
    $date = $faker->dateTimeThisMonth;

    return [
        'title' => $faker->sentence(),
        'content' => $faker->paragraph(),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});

$factory->define(App\Attachment::class, function (Faker\Generator $faker) {
    return [
        'filename' => sprintf("%s %s", str_random(), $faker->randomElement(['jpg','png','zip','tar']))
    ];
});

$factory->define(App\Comment::class, function (Faker\Generator $faker) {
    $articleIds = App\Article::pluck('id')->toArray(); // pluck 메서드는 지정된 키의 값을 검사한다.
    $userIds = App\User::pluck('id')->toArray();

    return [
        'content' => $faker->paragraph,
        'commentable_type' => function () use ($faker, $articleIds) {
            return $faker->randomElement($articleIds);
        },
        'user_id' => function () use ($faker, $userIds) {
            return $faker->randomElement($userIds);
        },
    ];
});

$factory->define(App\Vote::class, function (Faker\Generator $faker) {
    $up = $faker->randomElement([true, false]);
    $down = !$up;
    // votes 테이블의 레코드 하나는 투표 한번을 의미 즉 votes.up 열과 votes.down 열 중 하나만 1 값을 가질 수 있다.
    $userIds = App\User::pluck('id')->toArray();

    return [
        'up' => $up ? 1 : null,
        'down' => $down ? 1 : null,
        'user_id' => function () use ($faker, $userIds) {
            return $faker->randomElement($userIds);
        }
    ];

});