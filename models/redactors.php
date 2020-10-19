<?php
include "connect.php";

header('Content-Type: application/json');

class RedactorList
{
    public $redactors;
}

class Redactor
{
    public $id;
    public $lname;
    public $fname;
    public $mail;

    function __construct($id, $lname, $fname, $mail)
    {
        $this->id = $id;
        $this->lname = $lname;
        $this->fname = $fname;
        $this->mail = $mail;
    }
}

$raw = new RedactorList;

$strSQL = "SELECT id_redactor, last_name, first_name, mail FROM redactor ORDER BY id_redactor";
$result = $objPdo->prepare($strSQL);
$result->execute();
foreach ($result as $row) {
    $raw->redactors[] = new Redactor($row['id_redactor'], $row['last_name'], $row['first_name'], $row['mail']);
}

echo json_encode($raw);
