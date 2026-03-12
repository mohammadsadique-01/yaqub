<?php

if (! function_exists('indian_number')) {

    function indian_number($number, $decimals = 2)
    {
        if ($number === null || $number === '') {
            return '0';
        }

        $number = round($number, $decimals);

        $integerPart = floor($number);
        $decimalPart = '';

        if ($decimals > 0) {
            $decimal = substr(strrchr($number, '.'), 1) ?: '';

            // remove if .00
            if ($decimal && intval($decimal) > 0) {
                $decimalPart = '.'.str_pad($decimal, $decimals, '0');
            }
        }

        $lastThree = substr($integerPart, -3);
        $restUnits = substr($integerPart, 0, -3);

        if ($restUnits != '') {
            $lastThree = ','.$lastThree;
        }

        $restUnits = preg_replace("/\B(?=(\d{2})+(?!\d))/", ',', $restUnits);

        return $restUnits.$lastThree.$decimalPart;
    }
}
