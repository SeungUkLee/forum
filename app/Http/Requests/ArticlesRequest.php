<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// 13.3 폼 리퀘스트 클래스 이용 115p
class ArticlesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    // 라라벨은 리디렉션이 발생할 때마다 세션에 여러 가지 정보를 저장한다.
    // 유효성 검사 오류 있을 때도 리디렉션이 발생하며 폼에 입력 값을 돌려주기 위해 세션을 이용한다.
    // 파일을 원소로 가지는 배열은 세션에 저장할 때 예외가 발생할 가능성이 크다
    // $dontFlash 프로퍼티는 유효성 검사에서 세션 저장을 하지 않을 필드를 정의한다.
    // 로그인 폼에서 비밀번호의 입력 값 유지가 되지 않는 것은 바로 이 프로퍼티 덕분이다.
    protected $dontFlash = ['files'];


    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required'],
            'content' => ['required' , 'min:10'],
            'tags' => ['required', 'array'], // tags => 'required|array' 와 같다.
            'files' => ['array'],
            'files.*' => ['mimes:jpg,png,zip,tar', 'max:300000'], // 폼을 배열로 전송한거, mimes 는 파일 형식을 검사 max 단위는 KB
            // 참고로 files.* 는 라라벨 5.2 부터 스는 배열 유효성 검사 문법
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute은(는) 필수 입력 항목입니다.',
            'min' => ':attribute은(는) 최소 :min 글자 이상이 필요합니다.',
        ];
    }

    public function attributes()
    {
        return [
            'title' => '제목',
            'content' => '본문',
        ];
    }
}
