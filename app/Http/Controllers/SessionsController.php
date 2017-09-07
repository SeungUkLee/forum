<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'destroy']);
    }

    public function create() {
        return view('sessions.create');
    }

    public function store(Request $request) {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if(!auth()->attempt($request->only('email', 'password'), $request->has('remember'))) {
//            flash('이메일 또는 비밀번호가 맞지 않습니다.');
//            return back()->withInput();
            return $this->respondError('이메일 또는 비밀번호가 맞지 않습니다.');
        }

        if(!auth()->user()->activated) {
            auth()->logout();
            flash()->warning('가입 확인해 주십시오.');
            return redirect('/');
        }

        flash(auth()->user()->name . '님 환영합니다.');
        return redirect()->intended('home');
        // auth 미들웨어가 작동해서 로그인 페이지로 들어왔을 때 intended 메서드로 사용자가 원래 접근하려고 했던 URL로 리다이렉션 해준다.
    }

    public function destroy() {
        auth()->logout();
        flash('안녕히 가세요');

        return redirect('/');
    }

    public function respondError($message) {
        flash()->error($message);

        return back()->withInput();
    }
}
