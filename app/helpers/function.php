<?php
function getDateFormat($date, $format) {
    if ($date) {
        $dateObject = date_create($date);
        return date_format($dateObject, $format);
    }
    return null;
}