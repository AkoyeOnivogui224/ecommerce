<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AmountExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('amount', [$this, 'amount'])
        ];
    }

    public function amount($value, string $symbol = 'GNF', string $decsep = ',', string $throusandsep = '')
    {
        $finalValue = $value / 100;

        $finalValue = number_format($finalValue, 2, $decsep, $throusandsep);

        return $finalValue . ' ' . $symbol;
    }
}
