<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MeetingCancelled extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $date;

    // Kita simpan tajuk & tarikh saja sebab data meeting akan dipadam
    public function __construct($title, $date)
    {
        $this->title = $title;
        $this->date = $date;
    }

    public function build()
    {
        return $this->subject('PEMBATALAN: Aktiviti ' . $this->title . ' Dibatalkan')
                    ->view('emails.cancelled');
    }
}
