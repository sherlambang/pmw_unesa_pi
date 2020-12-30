<?php

namespace PMW\Support;

class Dana
{

    /**
     * Melakukan format untuk sebuah nominal uang dalam
     * bentuk rupiah
     *
     * @param int $currency
     * @return string
     */
    public function format($currency)
    {
        return "Rp " . number_format($currency, 0, ',', '.');
    }

}