<?php
function httpRequest($path, $data, $method = 'POST')
{
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => $method,
            'content' => http_build_query($data)
        )
    );
    $context = stream_context_create($options);
    return file_get_contents($path, false, $context);
}
