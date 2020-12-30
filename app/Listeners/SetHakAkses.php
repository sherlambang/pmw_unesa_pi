<?php

namespace PMW\Listeners;

use PMW\Events\UserTerdaftar;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use PMW\Models\Mahasiswa;
use PMW\User;
use PMW\Models\HakAkses;
use PMW\Support\RequestStatus;

class SetHakAkses
{

    private $user;

    private $hakAkses;

    /**
     * Handle the event.
     *
     * @param  UserTerdaftar $event
     * @return void
     */
    public function handle(UserTerdaftar $event)
    {
        $this->user = $event->user;

        if (count($event->hakAkses) > 0) {
            $this->hakAkses = $event->hakAkses;
            $this->registrasiOlehAdmin();
        } else
            $this->registrasiManual();
    }

    /**
     * Jika penambahan user dilakukan secara menual oleh user itu sendiri
     * melalui form registrasi/daftar
     *
     * @return void
     */
    private function registrasiManual()
    {
        if (strlen((string)$this->user->id) == 11) {

            // Set user sebagai mahasiswa
            Mahasiswa::create([
                'id_pengguna' => $this->user->id,
                'ipk' => 0
            ]);

            // Mengatur hak akses sebagai anggota
            $this->user->hakAksesPengguna()
                ->attach(HakAkses::where('nama', HakAkses::ANGGOTA)->first(), [
                    'status_request' => RequestStatus::APPROVED
                ]);

        } else if (strlen((string)$this->user->id) == 18 || strlen((string)$this->user->id)) {

            // jika panjang id sesuai panjang NIP atau NIDN
            $this->user->update([
                'request' => true
            ]);
        }
    }

    /**
     * Jika penambahan user dilakukan oleh admin
     *
     * @return void
     */
    private function registrasiOlehAdmin()
    {
        foreach ($this->hakAkses as $value) {
            if ($value == 'Anggota') {
                Mahasiswa::create([
                    'id_pengguna' => $this->user->id
                ]);
            }

            User::find($this->user->id)
                ->hakAksesPengguna()
                ->attach(
                    HakAkses::where('nama', $value)->first(),
                    ['status_request' => RequestStatus::APPROVED]
                );
        }
    }
}
