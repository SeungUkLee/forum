<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearOrphanedAttachments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'my:coa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '고아가 된 첨부 파일을 삭제합니다...';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $orphaned = \App\Attachment::whereNull('article_id')
            ->where('created_at', '<', \Carbon\Carbon::now()->subWeek())->get();
        // attachments.article_id 열이 NULL임과 동시에 create_at 열이 일주일 이상 지난 레코드를 삭제한다.

        foreach ($orphaned as $attachment) {
            $path = attachments_path($attachment->filename);
            \File::delete($path);
            $attachment->delete();
            $this->line('삭제됨  : '.$path);
        }

        $this->info('고아가 된 파일이 전부 청소되었습니다..');

        return ;
    }
}
