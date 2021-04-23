<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class SeedEmailCode extends Mailable
{
    use Queueable, SerializesModels;
    protected $email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        //
        $this->email=$email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $code = rand(1000,99999);
        //缓存的门面方法  第一个参数为键  第二个参数为值  第三个参数是有效时间
        Cache::put($this->email,$code,now()->addMinute(15));
        return $this->view('email.seed-code',['code'=>$code]);
    }
}
