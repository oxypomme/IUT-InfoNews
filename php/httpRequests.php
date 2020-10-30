<?php
$api_path = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['REQUEST_URI'], 1) . '/api/';
$index_folder = 1;
while (strpos(@get_headers($api_path . 'connect.php')[0], '404')) {
    $index_folder += 1;
    $api_path = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['REQUEST_URI'], $index_folder) . '/api/';
}
unset($index_folder);

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
    return file_get_contents($GLOBALS['api_path'] . $path, false, $context);
}
