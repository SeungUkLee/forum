<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $event->user->last_login = \Carbon\Carbon::now();

        return $event->user->save();
    }

    public function subscribe(\Illuminate\Events\Dispatcher $events) {
        // $events->listen() 14장 참고
        // 이벤트 서비스 프로바이저가 이벤트와 리스너를 연결하지 않고 이벤트 구독자에게 이벤트 매칭을 위임한 것이라고 이해하자..
        $events->listen(
            \App\Events\UserCreated::class,
            __CLASS__ . '@onUserCreated'
        ); // UserCreated 이벤트를 자기 자신의 onUserCreated() 메서드에 연결 (__CLASS__는 자신을 포함한 클래스의 이름을 값으로 가진 상수)

        // $events->listen() 구문을 여러 개 써서 이벤트와 처리 로직을 연결할 수 있다.
        $events->listen(
            \App\Events\PasswordRemindCreated::class,
            __CLASS__ . '@onPasswordRemindCreated'
        );
    }

    // 메서드 이름만 다를뿐, handle() 메서드와 용법이 같다.
    public function onUserCreated(\App\Events\UserCreated $event) {
        $user = $event->user;
        \Mail::send('emails.auth.confirm', compact('user'), function ($message) use ($user) {
            $message->to($user->email);
            $message->subject(
                sprintf('[%s] 회원 가입을 확인해 주세요', config('app.name'))
            );
        });
    }

    public function onPasswordRemindCreated(\App\Events\PasswordRemindCreated $event) {
        \Mail::send('emails.passwords.reset', ['token' => $event->token],
            function ($message) use ($event) {
                $message->to($event->email);
                $message->subject(
                    sprintf('[%s] 비밀번호를 초기화하세요', config('app.name'))
                );
            }
        );
    }
}
