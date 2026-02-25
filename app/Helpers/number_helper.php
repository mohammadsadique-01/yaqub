<?php

if (! function_exists('indian_number')) {

    function indian_number($number, $decimals = 2)
    {
        if ($number === null || $number === '') {
            return '0';
        }

        return number_format((float) $number, $decimals, '.', ',');
    }
}
