<?php

namespace App\Jobs;

use CURLFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $email;
    protected $to;
    protected $fromName;

    public function __construct($email, $to, $fromName)
    {
        $this->email = $email;
        $this->to = $to;
        $this->fromName = $fromName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $filename = $this->email->file;
        if ($filename !== null) {
            $file_name_with_full_path = public_path('uploads/invoice/' . $filename);
            $filetype = "pdf/application";
            $key = env('MAIL_API_KEY');
            $url = 'https://api.elasticemail.com/v2/email/send?' . http_build_query([
                    'apiKey' => $key,
                    'subject' => $this->email->title,
                    'from' => 'no-reply@applygermany.net',
                    'fromName' => $this->fromName,
                    'bodyHtml' => $this->email->asHtml($this->fromName != 'Apply Germany'),
                    'to' => $this->to,
                    'file_1' => new CurlFile($file_name_with_full_path, $filetype, $filename)
                ]);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, []);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            $re = curl_exec($ch);
            curl_close($ch);
        } else {
            $key = env('MAIL_API_KEY');
            $url = 'https://api.elasticemail.com/v2/email/send?' . http_build_query([
                    'apiKey' => $key,
                    'subject' => $this->email->title,
                    'from' => 'no-reply@applygermany.net',
                    'fromName' => $this->fromName,
                    'bodyHtml' => $this->email->asHtml($this->fromName != 'Apply Germany'),
                    'to' => $this->to,
                ]);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, []);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            $re = curl_exec($ch);
            curl_close($ch);
        }
    }
}
