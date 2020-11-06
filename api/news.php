<?php
include "connect.php";

header('Content-Type: application/json');

class NewsList
{
    public $news;
    public $sucess = true;
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
        $this->id = utf8_encode($id);
        $this->theme = utf8_encode($theme);
        $this->content = utf8_encode($content);
        $this->date = utf8_encode($date);
        $this->redactor = utf8_encode($redactor);
        $this->lang = utf8_encode($lang);
    }
}

$raw = new NewsList;
if (!isset($_POST['method']) or $_POST['method'] == 'GET') {
    $sort = (isset($_GET['Sort']) ? strtoupper($_GET['Sort']) : 'DESC');
    $theme = (isset($_GET['Theme']) ? intval($_GET['Theme']) : '');
    $lang = (isset($_GET['Lang']) ? strtolower($_GET['Lang']) : '');
    $id = (isset($_GET['ID']) ? intval($_GET['ID']) : -1);

    $isFirstFilter = true;

    $sqlstr = 'SELECT * FROM news';
    if ($theme != '') {
        $sqlstr .= ' WHERE id_theme = ' . $theme;
        $isFirstFilter = false;
    }
    if ($lang != '') {
        $sqlstr .= (!$isFirstFilter ? ' AND' : ' WHERE') . ' language = "' . $lang . '"';
        $isFirstFilter = false;
    }
    if ($id > 0) {
        $sqlstr .= (!$isFirstFilter != '' ? ' AND' : ' WHERE') . ' id_news = "' . $id . '"';
        $isFirstFilter = false;
    }
    $sqlstr .= " ORDER BY date_news $sort";
    $result = $objPdo->prepare($sqlstr);
} else {
    if ($_POST['method'] == 'NEW') {
        $result = $objPdo->prepare("INSERT INTO `news`(`id_theme`, `content`, `id_redactor`, `language`) VALUES (:theme, :content, :redactor, :lang)");
        if (isset($_POST['theme']) and isset($_POST['content']) and isset($_POST['idredact']) and isset($_POST['lang'])) {
            $result->bindValue('theme', htmlentities($_POST['theme']), PDO::PARAM_INT);
            $result->bindValue('content',  $_POST['content'], PDO::PARAM_STR);
            $result->bindValue('redactor', htmlentities($_POST['idredact']), PDO::PARAM_INT);
            $result->bindValue('lang', htmlentities($_POST['lang']), PDO::PARAM_STR);
        } else
            $raw->sucess = "missing arguments";
    } else  if ($_POST['method'] == 'UPDATE') {
        $result = $objPdo->prepare("UPDATE `news` SET `id_theme`=:theme, `content`=:content, `language`=:lang WHERE `id_news`=:id");
        if (isset($_POST['ID']) and isset($_POST['theme']) and isset($_POST['content']) and isset($_POST['lang'])) {
            $result->bindValue('theme', htmlentities($_POST['theme']), PDO::PARAM_STR);
            $result->bindValue('content', $_POST['content'], PDO::PARAM_STR);
            $result->bindValue('lang', htmlentities($_POST['lang']), PDO::PARAM_STR);
            $result->bindValue('id', htmlentities($_POST['ID']), PDO::PARAM_INT);
        } else
            $raw->sucess = "missing arguments";
    } else  if ($_POST['method'] == 'DELETE') {
        $result = $objPdo->prepare("DELETE FROM `news` WHERE `id_news`=:id");
        if (isset($_POST['ID'])) {
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
        $raw->news[] = new News($row['id_news'], $row['id_theme'], $row['content'], $row['date_news'], $row['id_redactor'], $row['language']);
    }
}

echo json_encode($raw);
