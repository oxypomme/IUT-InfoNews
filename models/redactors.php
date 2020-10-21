<?php
include "connect.php";
include "redactorClass.php";

header('Content-Type: application/json');

class RedactorList
{
    public $redactors;
    public $sucess = true;
}

$raw = new RedactorList;
if (!isset($_POST['method']) or $_POST['method'] == 'GET') {
    $id = $_GET['ID'];
    if ($id == '')
        $result = $objPdo->prepare("SELECT id_redactor, last_name, first_name, mail FROM redactor ORDER BY id_redactor");
    else {
        $result = $objPdo->prepare("SELECT id_redactor, last_name, first_name, mail FROM redactor WHERE id_redactor = :id");
        $result->bindValue('id', $id, PDO::PARAM_INT);
    }
} else {
    if ($_POST['method'] == 'NEW') {
        if (isset($_POST['lname']) and isset($_POST['fname']) and isset($_POST['mail']) and isset($_POST['pass'])) {
            $result = $objPdo->prepare("INSERT INTO `redactor`(`last_name`, `first_name`, `mail`, `passwrd`) VALUES (:lname, :fname, :mail, :pass)");
            $result->bindValue('lname', htmlentities($_POST['lname']), PDO::PARAM_STR);
            $result->bindValue('fname', htmlentities($_POST['fname']), PDO::PARAM_STR);
            $result->bindValue('mail', htmlentities($_POST['mail']), PDO::PARAM_STR);
            $result->bindValue('pass', crypt(htmlentities($_POST['pass']), '$2a$07$usesomesillystringforsalt'), PDO::PARAM_STR);
        } else
            $raw->sucess = "missing arguments";
    } else  if ($_POST['method'] == 'UPDATE') {
        if (isset($_POST['id']) and isset($_POST['lname']) and isset($_POST['fname']) and isset($_POST['mail']) and isset($_POST['pass'])) {
            $result = $objPdo->prepare("UPDATE `redactor` SET `last_name`=:lname, `first_name`=:fname, `mail`=:mail, `passwrd`=:pass WHERE `id_redactor`=:id");
            $result->bindValue('id', htmlentities($_POST['id']), PDO::PARAM_INT);
            $result->bindValue('lname', htmlentities($_POST['lname']), PDO::PARAM_STR);
            $result->bindValue('fname', htmlentities($_POST['fname']), PDO::PARAM_STR);
            $result->bindValue('mail', htmlentities($_POST['mail']), PDO::PARAM_STR);
            $result->bindValue('pass', crypt(htmlentities($_POST['pass']), '$2a$07$usesomesillystringforsalt'), PDO::PARAM_STR);
        } else
            $raw->sucess = "missing arguments";
    } else  if ($_POST['method'] == 'DELETE') {
        if (isset($_POST['id'])) {
            $result = $objPdo->prepare("DELETE FROM `redactor` WHERE `id_redactor`=:id");
            $result->bindValue('id', htmlentities($_POST['id']), PDO::PARAM_INT);
        } else
            $raw->sucess = "missing arguments";
    } else {
        $raw->sucess = "no method found";
    }
}

if (!$result->execute()) {
    if ($raw->sucess == true)
        $raw->sucess = "MySQL error";
} else
    foreach ($result as $row) {
        $raw->redactors[] = new Redactor($row['id_redactor'], $row['last_name'], $row['first_name'], $row['mail']);
    }

echo json_encode($raw);
