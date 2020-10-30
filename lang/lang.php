<?php
function getTrad($field)
{
    $lang = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'fr';
    if (file_exists('lang/' . $lang . '.json'))
        $file = file_get_contents('lang/' . $lang . '.json');
    else if (file_exists('../lang/' . $lang . '.json'))
        $file = file_get_contents('../lang/' . $lang . '.json');
    return json_decode($file)->$field;
}
