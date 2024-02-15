<?php

namespace App\Jobs;

use App\Mail\WebinarEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailWebinarJobTest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */


    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->data as $item) {
            Mail::to($item['email'])
                ->queue(new WebinarEmail(['email' => $item['email'], 'title' => 'وبینار تخصصی صفر تا صد مهاجرت تحصیلی به آلمان',
                    'name' => $item['name']], "ثبت نام وبینار"));
            Log::info('Send Webinar Email to: ' .$item['email'] . ' Name: ' . $item['name'] . ' ' . $item['family']);
        }
    }
}
