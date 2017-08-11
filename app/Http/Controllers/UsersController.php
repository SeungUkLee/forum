<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    // 이미 로그인한 사용자가 회원 가입 주소를 직접 입력하는 것을 막기위함.
    public function __construct()
    {
        $this->middleware('guest');
        // 라우트 정의할때 'middleware' 키워드로 쓸 수도 있지만 다음과 같이 적용할 수도 있다.
        // guest 는 auth 와 반대로 로그인하지 않은 사용자에게만 이 클래스의 메서드 사용을 허가 한다.
        // middleware 두번째 인자로 적용 범위를 한정할 수 있다.
    }

    public function create() {
        return view('users.create');
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
        $confirmCode = str_random(60);
        $user = \App\User::create([
            // name 필드의 입력 값을 조회. $request 객체는 라라벨이 주입한것.
            // 생성자에서 의존성을 주입하듯이 메서드에서도 타입 힌트만 정확히 써주면 라라벨이 인스턴스를 주입해 준다.
            // $requset 인스턴스 없으면 \Request::input('name') 파스드로 작성 가능
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'confirm_code' => $confirmCode,
        ]);


//        \Mail::send('emails.auth.confirm', compact('user'), function($message) use ($user) {
//            $message->to($user->email);
//            $message->subject(
//                sprintf('[%s] 회원 가입을 확인해 주세요', config('app.name'))
//            );
//        });
        // 223p 컨트롤러에서 가입확인 메일을 보내지 말고 이벤트를 던져서 이벤트 리스너에서 메일을 보내도록 하였다.
        event(new \App\Events\UserCreated($user));


//        auth()->login($user); // 생성한 사용자 객체로 로그인
//        flash(auth()->user()->name . '님 환영합니다.');

//        flash('가입하신 메일 계정으로 가입 확인 메일을 보내드렸습니다. 가입 확인하시고 로그인해 주세요.');
//        return redirect('/');
        return $this->respondCreated('가입하신 메일 계정으로 가입 확인 메일을 보내드렸습니다. 가입 확인하시고 로그인해 주세요.');
    }

    public function confirm($code) {
        $user = \App\User::whereConfirmCode($code)->first();

        if(!$user) {
            flash('URL이 정확하지 않습니다.');

            return redirect('/');
        }

        $user->activated = 1;
        $user->confirm_code = null;
        $user->save();

        auth()->login($user);
        flash(auth()->user()->name . '님 환영합니다. 가입 확인되었습니다.');

        return redirect('home');
    }

    public function respondCreated($message) {
        flash($message);

        return redirect('/');
    }

    public function getReset($token = null) {
        return view('passwords.reset', compact('token'));
    }
}
