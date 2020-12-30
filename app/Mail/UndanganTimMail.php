<?php

namespace PMW\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UndanganTimMail extends Mailable
{
    use Queueable, SerializesModels;

    private $nama;
    private $prodi;
    private $penerima;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nama, $prodi, $penerima)
    {
        $this->nama = $nama;
        $this->prodi = $prodi;
        $this->penerima = $penerima;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Undangan Pembentukan Tim | PMW UNESA')->view('mail.undangantim', [
            'nama' => $this->nama,
            'prodi' => $this->prodi,
            'penerima' => $this->penerima,
        ]);
    }
    
}
