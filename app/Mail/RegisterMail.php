<?php

namespace PMW\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class ini digunakan untuk melakukan pengiriman email
 * kepada user yang telah melakukan pendaftaran
 * 
 * @author BagasMuharom <bagashidayat@mhs.unesa.ac.id|bagashidayat45@gmail.com>
 */
class RegisterMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Menyimpan instance dari \PMW\User
     *
     * @var \PMW\User
     */
    private $user;

    /**
     * Menyimpan password (yang belum di enkripsi) untuk sementara
     * agar bisa dikirim ke email pengguna
     *
     * @var string
     */
    private $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Pendaftaran PMW Unesa')->view('mail.register',[
            'user' => $this->user,
            'password' => $this->password
        ]);
    }
}
