<?php
$inputErrors = array(
    'name' => false,
    'theme' => false,
    'text' => false,
    'lang' => false,
    'others' => false
);

$name = isset($_POST["name"]) ? htmlspecialchars($_POST['name']) : '';
$theme = isset($_POST["themes"]) ? htmlspecialchars($_POST['themes']) : '';
$text = isset($_POST["text"]) ? htmlspecialchars($_POST['text']) : '';
$lang = isset($_POST["lang"]) ? htmlspecialchars($_POST['lang']) : '';
$imgURL = isset($_POST["imgURL"]) ? htmlspecialchars($_POST['imgURL']) : '';

function showError($errorName)
{
    if ($GLOBALS['inputErrors'][$errorName])
        echo '<span class="error">' . $GLOBALS['inputErrors'][$errorName] . '</span>';
}

include 'api/connect.php';
include 'models/newsClass.php';

$base_path = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['REQUEST_URI']) . '/api/news.php';

if (session_id() == "")
    session_start();
if (isset($_POST['submit'])) {
    if (isset($_POST["name"]) && !empty($_POST['name'])) {
        if (isset($_POST['themes']) && !empty($_POST['themes'])) {
            if (isset($_POST['text']) && !empty($_POST['text'])) {
                if (isset($_POST['lang']) && !empty($_POST['lang'])) {
                    include 'httpRequests.php';

                    $content = new News($name, $text, $imgURL);
                    $data = array('theme' => $theme, 'content' => json_encode($content), 'lang' => $lang);
                    if (isset($_GET['ID'])) {
                        $data['ID'] = htmlspecialchars($_GET['ID']);
                        $data['method'] = 'UPDATE';
                    } else {
                        $data['method'] = 'NEW';
                        $data['idredact'] = $_SESSION['login'];
                    }
                    $result = httpRequest($base_path, $data);

                    if ($result === FALSE)
                        $inputErrors['others'] = 'Une erreur HTTP est survenue.';
                    else if (($err = json_decode($result)->sucess) !== true) {
                        $inputErrors['others'] = $err;
                    } else
                        header('Location:index.php');
                } else
                    $inputErrors['lang'] = "Langue incorrecte";
            } else
                $inputErrors['text'] = "Texte vide ou incorrect";
        } else
            $inputErrors['theme'] = "Theme vide ou incorrect";
    } else
        $inputErrors['name'] = "Nom vide ou incorrect";
} else if (isset($_POST['cancel'])) {
    header('Location:index.php');
}

if (isset($_GET['ID'])) {
    $path = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['REQUEST_URI']) . '/api/news.php?ID=' . htmlentities($_GET['ID']);
    $result = file_get_contents($path);
    if ($result !== false) {
        $news = json_decode($result);
        if ($news->sucess) {
            $news = $news->news[0];
            if (session_id() == "")
                session_start();
            if ($_SESSION['login'] != $news->redactor) {
                echo "<script lang=\"javascript\" type=\"text/javascript\">
                    alert(\"Vous n'êtes pas autorisé à modifier cet article !\");
                    window.location.href = 'index.php';
                </script>";
            }
            $theme = $news->theme;
            $content = json_decode($news->content);
            $name = $content->title;
            $text = $content->text;
            $imgURL = $content->imgURL;
            $lang = $news->lang; //BUG: didn't update the form ?
        }
    }
}
