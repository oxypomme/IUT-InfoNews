<?php
include "connect.php";

header('Content-Type: application/json');

class ThemeList
{
    public $themes;
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

$strSQL = "SELECT * FROM theme ORDER BY id_theme";
$result = $objPdo->prepare($strSQL);
$result->execute();
foreach ($result as $row) {
    $raw->themes[] = new Theme($row['id_theme'], $row['label'], $row['color'], $row['icon_theme']);
}

echo json_encode($raw);
