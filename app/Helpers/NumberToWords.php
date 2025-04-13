<?php

namespace App\Helpers;

class NumberToWords
{
    public static function convert($amount)
    {
        $f = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);

        $rupees = floor($amount);
        $paise = round(($amount - $rupees) * 100);

        $rupeesText = $rupees > 0 ? $f->format($rupees) . ' rupees' : '';
        $paiseText = $paise > 0 ? $f->format($paise) . ' paise' : '';

        if ($rupeesText && $paiseText) {
            return ucfirst("$rupeesText and $paiseText only");
        } elseif ($rupeesText) {
            return ucfirst("$rupeesText only");
        } elseif ($paiseText) {
            return ucfirst("$paiseText only");
        } else {
            return "Zero rupees only";
        }
    }
}
