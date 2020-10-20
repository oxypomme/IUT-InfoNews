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

$theme = intval($_GET['Theme']);
$sort = (isset($_GET['Sort']) ? strtoupper($_GET['Sort']) : 'DESC');

if ($theme == '')
    $result = $objPdo->prepare("SELECT * FROM news ORDER BY date_news $sort");
else {
    $result = $objPdo->prepare("SELECT * FROM news WHERE id_theme = :theme ORDER BY date_news $sort");
    $result->bindValue('theme', $theme, PDO::PARAM_INT);
}
$result->execute();
foreach ($result as $row) {
    $raw->news[] = new News($row['id_news'], $row['id_theme'], $row['content'], $row['date_news'], $row['id_redactor'], $row['language']);
}

echo json_encode($raw);
