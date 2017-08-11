<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    //224p
    // 이벤트 구독자(event subscriber)를 이용하면 하나의 리스터 클래스가 여러 개의 이벤트를 구독하고,
    // 클래스 내부에서 이벤트를 처리 할 수도 있다. $subscribe 에 UserEventListener를 등록함으로써
    // 이 클래스는 이벤트 리스너임과 동이세 이벤트 구독자가 된다.
    protected $subscribe = [
        \App\Listeners\UserEventListener::class,
    ];

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
//        'App\Events\SomeEvent' => [
//            'App\Listeners\EventListener',
//        ],
        \App\Events\ArticlesEvent::class => [
          \App\Listeners\ArticlesEventListener::class,
        ],
        \Illuminate\Auth\Events\Login::class => [
          \App\Listeners\UserEventListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
//        \Event::listen('article.created', function($article) {
//            var_dump('이벤트를 받았습니다. 받은 데이터(상태)는 다음과 같습니다.');
//            var_dump($article->toArray());
//        });
        //
//        \Event::listen('article.created', \App\Listeners\ArticlesEventListener::class);
        \Event::listen(\App\Events\ArticleCreated::class, \App\Listeners\ArticlesEventListener::class);
    }
}
