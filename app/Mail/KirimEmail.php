<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KirimEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = "Contoh Email dengan Lampiran";

    public $attachmentPath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($attachmentPath)
    {
        $this->attachmentPath = $attachmentPath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.example')
                    ->attach($this->attachmentPath, [
                        'as' => 'logo.png',
                        'mime' => 'image/png',
                    ]);
    }
}
