<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    // 163p 서비스 프로바이더 등록이 끝나면 이 메서드의 내용이 실행
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    // 라라벨에 뭔가를 등록하기 위한 메서드, 이 메서드 안에서 라라벨의 다른 서비스를 쓰지 않도록 주의!
    // 가령, 이벤트 처리 로직을 여기에 써서는 안된다. 이벤트 서비스가 아직 초기화 되지 않을 수도 있기 때문에..
    public function register()
    {
        if($this->app->environment('local')) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
