<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $title;
    public $file;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title, $data, $file)
    {
        $this->title = $title;
        $this->data = $data;
        $this->file = $file;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->file) {
            Log::info($this->file);
            return $this->view('admin.partials.mail')
                ->subject($this->title)
                ->with("title", $this->title)
                ->with("text", $this->data)
                ->attach($this->file);
        } else {
            return $this->view('admin.partials.mail')
                ->subject($this->title)
                ->with("title", $this->title)
                ->with("text", $this->data);
        }
    }
}
