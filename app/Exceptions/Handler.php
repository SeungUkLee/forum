<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    // 해당하는 예외는 라라벨 로그에 기록되지 않고 우리가 정의한 보고 로직도 타지않는다.
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */

    // report() 예외를 보고하는 메서드
    public function report(Exception $exception)
    {
        parent::report($exception); // 라라벨 로그에 예외를 기록하는 일을 한다.
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    // render() 예뢰를 화면에 표시하는 메서드
    // report() 메서드는 보고한 후 다음 로직을 수해하는 반면 render()는 return하여 HTTP응답을 반환하고 코드를 종료한다.
    public function render($request, Exception $exception)
    {
        // .env 의 APP_ENV = production 으로 하드코드로 바꾸어야함
        if(app()->environment('production')) {
            if($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response(view('errors.notice', [
                    'title' => '찾을 수 없습니다.',
                    'description' => '죄송합니다 요청하신 페이지가 없습니다.'
                ]));
            }
        }
        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }
}
