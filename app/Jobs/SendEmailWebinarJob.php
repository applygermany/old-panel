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

class SendEmailWebinarJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */


    protected $email;
    protected $name;
    protected $family;

    public function __construct($email, $name, $family)
    {
        $this->email = $email;
        $this->name = $name;
        $this->family = $family;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)
            ->send(new WebinarEmail(['email' =>$this->email, 'title' => 'وبینار تخصصی صفر تا صد مهاجرت تحصیلی به آلمان',
                'name' => $this->name], "ثبت نام وبینار"));
        Log::info('Send Webinar Email to: ' . $this->email. ' Name: '. $this->name . ' ' . $this->family);
    }
}
