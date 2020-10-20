<?php
include "connect.php";
include "redactorClass.php";

header('Content-Type: application/json');

class RedactorList
{
    public $redactors;
}

$raw = new RedactorList;


$id = $_GET['ID'];
if ($id == '')
    $result = $objPdo->prepare("SELECT id_redactor, last_name, first_name, mail FROM redactor ORDER BY id_redactor");
else {
    $result = $objPdo->prepare("SELECT id_redactor, last_name, first_name, mail FROM redactor WHERE id_redactor = :id");
    $result->bindValue('id', $id, PDO::PARAM_INT);
}
$result->execute();
foreach ($result as $row) {
    $raw->redactors[] = new Redactor($row['id_redactor'], $row['last_name'], $row['first_name'], $row['mail']);
}

echo json_encode($raw);
