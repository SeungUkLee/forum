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
        // 269p view composer
        // 태그 목록은 모든 뷰에서 필요하다. 컨트롤러에서 뷰를 반환할때 마다 태그 목록을 변수에 담아 넘기는 것은 깔끔하지 못하다.
        // 이때 사용할 수 있는 기능이 view composer 이다.
        view()->composer('*', function ($view) { // view() 함수에 composer() 메서드 이용. 첫번째 인자는 뷰이름, 두번째인자는 콜백
            // 콜백은 별도의 클래스로 추출할 수 있다. 콜백
            // 안에서는 인자로 받은 Illuminate\View\Factory $view 인스턴스에 with() 메서드 체인으로 데이터 바인딩
            $allTags = \Cache::rememberForever('tags.list', function() {
                // 태그 목록은 변경 가능성이 적어 rememberForever() 메서드를 가지고 캐시에다 반 영구적으로 저장.
                // 태그 목록에 변화가 있으면 $php artisan cache:clear 명령어 꼭 칠 것!
                return \App\Tag::all();
            });

            $view->with(compact('allTags'));
            $view->with('currentUser', auth()->user());
        });
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
