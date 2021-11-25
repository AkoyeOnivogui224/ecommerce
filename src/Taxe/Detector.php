<?php

namespace App\Taxe;

class Detector
{
    public function detecter(float $prix)
    {
        $tva = $prix * (20 / 100);
        if ($prix > 100) {
            return true;
        } else {
            return false;
        }
    }
}
