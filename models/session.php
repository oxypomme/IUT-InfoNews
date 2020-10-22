<?php
header('Content-Type: application/json');

class Session
{
    public $idlogin;
}

$raw = new Session;
if (session_id() == "")
    session_start();
$raw->idlogin = (isset($_SESSION['login']) ?  $_SESSION['login'] : '');

$result = new stdClass();
$name = (isset($_GET['Name']) ? $_GET['Name'] : '');
if ($name != '' && isset($raw->$name)) {
    $result->$name = $raw->$name;
} else if ($name == '')
    $result = $raw;
echo json_encode($result);
