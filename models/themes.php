<?php
include "connect.php";

header('Content-Type: application/json');

class ThemeList
{
    public $themes;
    public $sucess = true;
}

class Theme
{
    public $id;
    public $label;
    public $color;
    public $iconURL;

    function __construct($id, $label, $color, $iconURL)
    {
        $this->id = $id;
        $this->label = $label;
        $this->color = $color;
        $this->iconURL = $iconURL;
    }
}

$raw = new ThemeList;
if (!isset($_POST['method']) or $_POST['method'] == 'GET') {
    $id = $_GET['ID'];
    if ($id == '')
        $result = $objPdo->prepare("SELECT * FROM theme ORDER BY id_theme");
    else {
        $result = $objPdo->prepare("SELECT * FROM theme WHERE id_theme = :id");
        $result->bindValue('id', $id, PDO::PARAM_INT);
    }
} else {
    if ($_POST['method'] == 'NEW') {
        $result = $objPdo->prepare("INSERT INTO `theme`(`label`, `color`, `icon_theme`) VALUES (:label, :color, :iconURL)");
        if (isset($_POST['label']) and isset($_POST['color']) and isset($_POST['iconURL'])) {
            $result->bindValue('label', htmlentities($_POST['label']), PDO::PARAM_STR);
            $result->bindValue('color', htmlentities($_POST['color']), PDO::PARAM_STR);
            $result->bindValue('iconURL', htmlentities($_POST['iconURL']), PDO::PARAM_STR);
        } else
            $raw->sucess = "missing arguments";
    } else  if ($_POST['method'] == 'UPDATE') {
        $result = $objPdo->prepare("UPDATE `theme` SET `label`=:label, `color`=:color, `icon_theme`=:iconURL WHERE `id_theme`=:id");
        if (isset($_POST['label']) and isset($_POST['color']) and isset($_POST['iconURL']) and isset($_POST['id'])) {
            $result->bindValue('label', htmlentities($_POST['label']), PDO::PARAM_STR);
            $result->bindValue('color', htmlentities($_POST['color']), PDO::PARAM_STR);
            $result->bindValue('iconURL', htmlentities($_POST['iconURL']), PDO::PARAM_STR);
            $result->bindValue('id', htmlentities($_POST['id']), PDO::PARAM_INT);
        } else
            $raw->sucess = "missing arguments";
    } else  if ($_POST['method'] == 'DELETE') {
        $result = $objPdo->prepare("DELETE FROM `theme` WHERE `id_theme`=:id");
        if (isset($_POST['id'])) {
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
        $raw->themes[] = new Theme($row['id_theme'], $row['label'], $row['color'], $row['icon_theme']);
    }

echo json_encode($raw);
