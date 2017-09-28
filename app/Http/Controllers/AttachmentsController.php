<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttachmentsController extends Controller
{
    public function store(Request $request) {
        Log::info('$request : '.$request);
        Log::info('Request has File : '.$request->hasFile('files'));
        Log::info('Request article id : '.$request->input('article_id'));
        $attachments = [];

        // ArticlesController 에 있던 파일 업로드 요청 처리 로직을 별도의 컨트롤러로 옮김 -> 이유는 287p
        if($request->hasFile('files')) { // hasFile() 메서드로 files 필드 확인
            $files = $request->file('files'); // file() 메서드는 폼 필드로 넘어온 배열 형태의 파일 목록 조회

            foreach($files as $file) {
                // 같은 파일을 두 번 업로듣할 수 있다. 파일 간의 이름 충돌을 피하기 위해 앞에 랜덤 문자를 붙였다.
                // FILTER_SANITIZE_URL 필터를 사용하면, URL로 접근할때 안전하지 않은 문자는 필터링할 수 있다.
                // UploadFile 인스턴스의 getClientOriginalName() 메서드는 임시 파일의 이름이 아니라 사용자가 업로드한 원래 파일 이름을 반환
                // getClientSize() 도 같은 맥락
                $filename = str_random().filter_var($file->getClientOriginalName(), FILTER_SANITIZE_URL);
                $file->move(attachments_path(), $filename); // 파일을 원하는 위치로 옮기는 구문

//                $article->attachments()->create([
//                    'filename' => $filename,
//                    'bytes' => $file->getSize(),
//                    'mime' => $file->getClientMimeType()
//                    // 의사 결정이 필요한 부분
//                    // 파일을 저장할 때 파일 크기와 마임 타입을 테이블에 미리 써놓을 것이냐 ?
//                    // vs 모델을 조회할 때 파일시스템을 읽어서 크기와 마임 타입을 판단하고 반환할 것이냐?
//                    // 쓰기는 한 번이지만 읽기는 여러 번 발생 -> 따라서 먼저 써 놓는 것이 좀 더 현명하다.
//                ]);
                $payload = [ // 메타 데이터로 저장할 값들을 미리 준비
                    'filename' => $filename,
                    'bytes' => $file->getClientSize(),
                    'mime' => $file->getClientMimeType(),
                ];

                // articles_id 가 있고 없고의 로직을 분기
                // 있으면 글 수정 폼에서 보낸 요청이다. -> Article 모델과의 관계를 이용해서 메타 데이터 저장
                // 필드가 없으면 글 쓰기 폼에서 보낸 요청 -> Attachments 모델과의 관계를 이용해서 메타 데이터 저장
                $attachments[] = ($id = $request->input('article_id'))
                    ? \App\Article::findOrFail($id)->attachments()->create($payload)
                    : \App\Attachment::create($payload);
            }

        }
        return response()->json($attachments); // 배열형 파일 필드를 처리한 결과를 json 으로 반환
        // 만약 files 필드가 없는 HTTP 요청이 오면 비어있는 JSON 반환
    }

    public function destroy($id) {

    }
}
