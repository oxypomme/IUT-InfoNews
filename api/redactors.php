<?php
include "connect.php";

header('Content-Type: application/json');

class RedactorList
{
    public $redactors;
    public $sucess = true;
}

class Redactor
{
    public $id;
    public $lname;
    public $fname;
    public $mail;
    public $role;

    function __construct($id, $lname, $fname, $mail, $role)
    {
        $this->id = $id;
        $this->lname = $lname;
        $this->fname = $fname;
        $this->mail = $mail;
        $this->role = $role;
    }
}


$raw = new RedactorList;
if (!isset($_POST['method']) or $_POST['method'] == 'GET') {
    $id = (isset($_GET['ID']) ? intval($_GET['ID']) : -1);

    $isFirstFilter = true;

    $sqlstr = 'SELECT id_redactor, last_name, first_name, mail, `role` FROM redactor';
    if ($id > 0) {
        $sqlstr .= ' WHERE id_redactor = ' . intval($id);
        $isFirstFilter = false;
    }
    $sqlstr .= " ORDER BY id_redactor";
    $result = $objPdo->prepare($sqlstr);
} else {
    if ($_POST['method'] == 'NEW') {
        if (isset($_POST['lname']) and isset($_POST['fname']) and isset($_POST['mail']) and isset($_POST['pass'])) {
            $result = $objPdo->prepare("INSERT INTO `redactor`(`last_name`, `first_name`, `mail`, `passwrd`, `role`) VALUES (:lname, :fname, :mail, :pass, 0)");
            $result->bindValue('lname', htmlentities($_POST['lname']), PDO::PARAM_STR);
            $result->bindValue('fname', htmlentities($_POST['fname']), PDO::PARAM_STR);
            $result->bindValue('mail', htmlentities($_POST['mail']), PDO::PARAM_STR);
            $result->bindValue('pass', crypt(htmlentities($_POST['pass']), '$2a$07$usesomesillystringforsalt'), PDO::PARAM_STR);
        } else
            $raw->sucess = "missing arguments";
    } else  if ($_POST['method'] == 'UPDATE') {
        if (isset($_POST['ID']) and isset($_POST['lname']) and isset($_POST['fname']) and isset($_POST['mail']) and isset($_POST['pass'])) {
            $result = $objPdo->prepare("UPDATE `redactor` SET `last_name`=:lname, `first_name`=:fname, `mail`=:mail, `passwrd`=:pass WHERE `id_redactor`=:id");
            $result->bindValue('id', htmlentities($_POST['ID']), PDO::PARAM_INT);
            $result->bindValue('lname', htmlentities($_POST['lname']), PDO::PARAM_STR);
            $result->bindValue('fname', htmlentities($_POST['fname']), PDO::PARAM_STR);
            $result->bindValue('mail', htmlentities($_POST['mail']), PDO::PARAM_STR);
            $result->bindValue('pass', crypt(htmlentities($_POST['pass']), '$2a$07$usesomesillystringforsalt'), PDO::PARAM_STR);
        } else
            $raw->sucess = "missing arguments";
    } else  if ($_POST['method'] == 'DELETE') {
        if (isset($_POST['ID'])) {
            $result = $objPdo->prepare("DELETE FROM `redactor` WHERE `id_redactor`=:id");
            $result->bindValue('id', htmlentities($_POST['ID']), PDO::PARAM_INT);
        } else
            $raw->sucess = "missing arguments";
    } else {
        $raw->sucess = "no method found";
    }
}

if (!$result->execute()) {
    if ($raw->sucess === true)
        $raw->sucess = "MySQL error";
} else {
    foreach ($result as $row) {
        $raw->redactors[] = new Redactor($row['id_redactor'], $row['last_name'], $row['first_name'], $row['mail'], $row['role']);
    }
}

echo json_encode($raw);
