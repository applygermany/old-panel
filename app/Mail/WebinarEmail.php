<?php

namespace App\Mail;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WebinarEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $title;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $title)
    {
        $this->data = $data;
        $this->title = $title;
    }

    public function asHtml($path)
    {
        return view('admin.partials.webinarMail')->with(['title' => $this->title, 'data' => $this->data])->render();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        try {
            return $this->view('admin.partials.webinarMail')->subject($this->title)->with("title", $this->title)->with("data", $this->data);
        } catch (Exception $e) {
            return 0;
        }
    }
}
