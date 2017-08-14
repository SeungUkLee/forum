<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PasswordsController extends Controller
{
    // 비밀번호 변경 로직
    // 1. route('remind.create') (비밀번호 바꾸기 신청 폼)에서 사용자가 회원 가입할 때 사용했던 이메일 입력, 폼을 전송
    // 2. 사용자 요청을 받아 토근 생성, 토큰과 결합한 비밀번호 바꾸기 요청 URL을 만들어 사용자에게 메일로 보냄
    // 3. 사용자가 메일에 포함된 URL로 요청하면 비밀번호 바꾸기 폼을 제시. 사용자는 새로운 비밀번호를 입력하고 폼을 전송
    // 4. 폼 요청을 받으면 토큰과 일치하는 사용자를 찾아 비밀번호를 업데이트 토큰 레코드는 삭제한다.
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function getRemind() {
        return view('passwords.remind');
    }

    public function postRemind(Request $request) {
        $this->validate($request, ['email' => 'required|email|exists:users']);

        $email = $request->get('email');
        $token = str_random(64);

        // password_resets 테이블에 해당하는 엘로퀀트 모델을 만들지 않았으므로 DB 파서드와 쿼리 빌더를 이용
        \DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => \Carbon\Carbon::now()->toDateString()
            // (new DateTime)->format('Y-m-d H:i:s') 와 같이 써도 무방, Carbon은 날짜와 시간값을 편하게 조작하게 도와주는 컴포넌트
        ]);

        event(new \App\Events\PasswordRemindCreated($email, $token));

//        \Mail::send('emails.passwords.reset', compact('token'), function ($message) use ($email) {
//            $message->to($email);
//            $message->subject(
//                sprintf('[%s] 비밀번호를 초기화하세요', config('app.name'))
//            );
//        });

        flash('비밀번호를 바꾸는 방법을 다음 메일이 발송되었습니다. 메일을 확인해 주십시오.');

        return redirect('/');
    }

    public function getReset($token = null) {
        return view('passwords.reset', compact('token'));
    }

    public function postReset(Request $request) {
        $this->validate($request, [
            'email' => 'required|email|exists:users',
            'password' => 'required|confirmed',
            'token' => 'required'
        ]);

        $token = $request->get('token');

        if(! \DB::table('password_resets')->whereToken($token)->first()) {
            flash('URL이 정확하지 않습니다.');

            return back()->withInput();
        }

        \App\User::whereEmail($request->input('email'))->first()->update([
            'password' => bcrypt($request->input('password'))
        ]);

        \DB::table('password_resets')->whereToken($token)->delete();

        flash('비밀번호를 바꾸었습니다. 새로운 비밀번호로 로그인 하세요.');

        return redirect('/');
    }
}
