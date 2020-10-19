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

    function __construct($id, $theme, $content, $date, $redactor)
    {
        $this->id = $id;
        $this->theme = $theme;
        $this->content = $content;
        $this->date = $date;
        $this->redactor = $redactor;
    }
}

// class NewsContent
// {
//     public $title;
//     public $text;
//     public $imgURL;

//     function __construct($title, $text, $imgURL)
//     {
//         $this->title = $title;
//         $this->text = $text;
//         $this->imgURL = $imgURL;
//     }
// }

$raw = new NewsList;

$theme = $_GET['Theme'];
$strSQL = "SELECT * FROM news WHERE theme LIKE '$theme%' ORDER BY date_news";
$result = $objPdo->prepare($strSQL);
$result->execute();
foreach ($result as $row) {
    $raw->news[] = new News($row['id_news'], $row['id_theme'], $row['content'], $row['date_news'], $row['id_redactor']);
}

echo json_encode($raw);
