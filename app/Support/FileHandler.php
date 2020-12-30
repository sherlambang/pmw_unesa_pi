<?php

namespace PMW\Support;

use Illuminate\Support\Facades\Auth;
use PMW\Models\Pengaturan;
use PMW\Facades\FileHandler as FH;

trait FileHandler
{

    /**
     * Mengunggah berkas
     *
     * @param  $file
     * @return string
     */
    public function unggahBerkas($file)
    {
        return FH::save($this->dir, $file);
    }

    /**
     * Mengecek apakah berkas yang diunggah valid atau tidak
     *
     * @param $berkas
     * @return bool
     */
    private function berkasValid($berkas)
    {
        // mengambil ekstensi berkas
        $formatBerkas = $berkas->getClientOriginalExtension();

        if (is_array($this->validExtension)) {
            if (in_array($formatBerkas, $this->validExtension))
                return true;
        } elseif (is_string($this->validExtension)) {
            if ($formatBerkas === $this->validExtension)
                return true;
        }

        return false;
    }

    /**
     * Memastikan bahwa user dapat mengunggah proposal
     *
     * @param bool $final
     * @return boolean
     */
    private function bolehUnggah($final = false)
    {
        if($final)
            return Auth::user()->mahasiswa()->bisaUnggahProposalFinal() || Auth::user()->mahasiswa()->bisaEditProposalFinal();

        return Auth::user()->mahasiswa()->bisaUnggahProposal() || Auth::user()->mahasiswa()->bisaEditProposal();
    }

}
