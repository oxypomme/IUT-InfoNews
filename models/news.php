<?php
include "connect.php";

header('Content-Type: application/json');

class NewsList
{
    public $news;
    public $sucess;
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
if (!isset($_POST['method']) or $_POST['method'] == 'GET') {
    $theme = intval($_GET['Theme']);
    $sort = (isset($_GET['Sort']) ? strtoupper($_GET['Sort']) : 'DESC');

    if ($theme == '')
        $result = $objPdo->prepare("SELECT * FROM news ORDER BY date_news $sort");
    else {
        $result = $objPdo->prepare("SELECT * FROM news WHERE id_theme = :theme ORDER BY date_news $sort");
        $result->bindValue('theme', $theme, PDO::PARAM_INT);
    }
} else {
    if ($_POST['method'] == 'NEW') {
        $result = $objPdo->prepare("INSERT INTO `news`(`id_theme`, `content`, `id_redactor`, `language`) VALUES (:theme, :content, :redactor, :lang)");
        if (isset($_POST['theme']) and isset($_POST['content']) and isset($_POST['mail']) and isset($_POST['lang'])) {
            $redactResult = $objPdo->prepare("SELECT id_redactor FROM `redactor` WHERE mail = '" . $_POST['mail'] . "' LIMIT 1");
            if (!$redactResult->execute())
                $raw->sucess = "MySQL error when getting redactor ID";
            else
                foreach ($redactResult as $row) {
                    $result->bindValue('theme', htmlentities($_POST['theme']), PDO::PARAM_INT);
                    $result->bindValue('content',  $_POST['content'], PDO::PARAM_STR);
                    $result->bindValue('redactor', htmlentities($row['id_redactor']), PDO::PARAM_INT);
                    $result->bindValue('lang', htmlentities($_POST['lang']), PDO::PARAM_STR);
                    break;
                }
        } else
            $raw->sucess = "missing arguments";
    } else  if ($_POST['method'] == 'UPDATE') {
        $result = $objPdo->prepare("UPDATE `redactor` SET `id_theme`=:theme, `content`=:content, `language`=:lang WHERE `id_news`=:id");
        if (isset($_POST['id']) and isset($_POST['theme']) and isset($_POST['content']) and isset($_POST['lang'])) {
            $result->bindValue('theme', htmlentities($_POST['theme']), PDO::PARAM_STR);
            $result->bindValue('content', $_POST['content'], PDO::PARAM_STR);
            $result->bindValue('lang', htmlentities($_POST['lang']), PDO::PARAM_STR);
            $result->bindValue('id', htmlentities($_POST['id']), PDO::PARAM_INT);
        } else
            $raw->sucess = "missing arguments";
    } else  if ($_POST['method'] == 'DELETE') {
        $result = $objPdo->prepare("DELETE FROM `news` WHERE `id_news`=:id");
        if (isset($_POST['id'])) {
            $result->bindValue('id', htmlentities($_POST['id']), PDO::PARAM_INT);
        } else
            $raw->sucess = "missing arguments";
    } else {
        $raw->sucess = "no method found";
    }
}

$result->execute();
foreach ($result as $row) {
    $raw->news[] = new News($row['id_news'], $row['id_theme'], $row['content'], $row['date_news'], $row['id_redactor'], $row['language']);
}

echo json_encode($raw);
