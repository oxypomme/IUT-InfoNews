<?php
include "connect.php";

header('Content-Type: application/json');

class NewsList
{
    public $news;
}

class News
{
    public $id;
    public $theme;
    public $content;
    public $date;
    public $redactor;
    public $lang;

    function __construct($id, $theme, $content, $date, $redactor, $lang)
    {
        $this->id = $id;
        $this->theme = $theme;
        $this->content = $content;
        $this->date = $date;
        $this->redactor = $redactor;
        $this->lang = $lang;
    }
}

$raw = new NewsList;

$theme = $_GET['Theme'];
if ($theme != "")
    $strSQL = "SELECT * FROM news WHERE theme LIKE '$theme%' ORDER BY date_news";
else
    $strSQL = "SELECT * FROM news ORDER BY date_news";
$result = $objPdo->prepare($strSQL);
$result->execute();
foreach ($result as $row) {
    $raw->news[] = new News($row['id_news'], $row['id_theme'], $row['content'], $row['date_news'], $row['id_redactor'], $row['language']);
}

echo json_encode($raw);
