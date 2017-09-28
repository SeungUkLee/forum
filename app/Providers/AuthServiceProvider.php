<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        // 라우팅과 이벤트가 그랬듯이 콜백에 담긴 처리 로직을 별도의 파일로 빼낼 수 있다
        // 별도의 클래스로 추출한 권한 부여 로직을 정책이라 부르는데 이 문장을 정책을 등록하는 구문이다.
//        $this->registerPolicies();


        // 266p
        // before 메서드는 다른 권한 검사를 처리하기 전에 먼저 실행된다, 마찬가지로 인자로 받은 콜백은 true, false 를 반환
        // 우리 예제는 지금 로그인한 사용자가 최고 관리자가 아닐 경우에만 다음 권한 검사 로직을 타게 된다.
        // git 예제랑 소스코드가 조금 다름....
        Gate::before(function ($user) {
//            return $user->isAdmin();
            if($user->isAdmin()) return true;
            // return false 가 되버리면 update , delete Gate 를 확인하지않더라.
        });

//        261p
//        Gate 파서드를 이용해서 권한과 처리 로직을 정의
//        define() 메서드의 첫 번째 인자는 권한의 이름, 두 번째 인자는 권한 부여 로직을 담은 콜백
        Gate::define('update', function($user, $model) {
            return $user->id === $model->user_id;
        });

        Gate::define('delete', function($user, $model) {
            return $user->id === $model->user_id;
        });
    }
}
