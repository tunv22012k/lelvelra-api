<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMail implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $template;
    protected $data;
    protected $to;
    protected $subject;
    protected $files;

    /**
     * Create a new job instance.
     */
    public function __construct(
        string $template,
        array $data,
        string $to,
        string $subject,
        array $files = [],
    ) {
        $this->template = $template;
        $this->data     = $data;
        $this->to       = $to;
        $this->subject  = $subject;
        $this->files    = $files;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $subject        = $this->subject;
        $to             = $this->to;
        $files          = $this->files;
        $template       = $this->template;
        $data           = $this->data;

        try {
            Mail::send(
                $template,
                $data,
                function ($message) use ($subject, $to, $files) {
                    $message->to($to);
                    $message->subject($subject);

                    foreach ($files as $file) {
                        $message->attach($file);
                    }
                }
            );
        } catch (\Throwable $e) {
            Log::channel('mail')->info("********************BEGIN LOG EMAIL MESSAGE HAS BEEN SENT*******************");
            Log::channel('mail')->info("Time    : " . Carbon::now()->format('Y/m/d H:i'));
            Log::channel('mail')->info("From    : " . env('MAIL_FROM_ADDRESS'));
            Log::channel('mail')->info("To      : " . $to);
            Log::channel('mail')->info("Subject : " . $subject);
            Log::channel('mail')->info("Status  : NG ( " . $e->getMessage() . " )");
            Log::channel('mail')->info("********************END LOG EMAIL MESSAGE HAS BEEN SENT*********************");

            throw $e;
        }
    }
}
