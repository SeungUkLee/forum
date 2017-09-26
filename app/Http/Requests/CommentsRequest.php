<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
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
            'content' => ['required', 'min:10'],
            'parent_id' => ['numeric', 'exists:comments,id']
            // exists:comments,id : 폼 데이터로 넘어온 parent_id 필드 값은 comments.id 열에 있는 값이어야 한다. 예를 들어,
            // 어떤 사용자가 답글을 쓰고 있는 도중에 원본 댓글 사용자가 자신의 댓글을 삭제할 수 있다.
        ];
    }
}
