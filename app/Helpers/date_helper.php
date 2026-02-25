<?php

if (! function_exists('format_date')) {

    function format_date($date, $format = 'd M Y')
    {
        if (! $date) {
            return '-';
        }

        return \Carbon\Carbon::parse($date)->format($format);
    }
}
