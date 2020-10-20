<?php
$inputErrors = array(
    'name' => false,
    'theme' => false,
    'text' => false,
    'lang' => false,
    'others' => false
);

$name = '';
$theme = '';
$text = '';
$lang = '';
$imgURL = '';

function showError(string $errorName)
{
    if ($GLOBALS['inputErrors'][$errorName])
        echo '<span class="error">' . $GLOBALS['inputErrors'][$errorName] . '</span>';
}

include 'models/connect.php';
include 'models/newsClass.php';
if (session_id() == "")
    session_start();
if (isset($_POST['submit'])) {
    if (isset($_POST["name"]) && !empty($_POST['name'])) {
        $name = htmlspecialchars($_POST['name']);
        if (isset($_POST['themes']) && !empty($_POST['themes'])) {
            $theme = htmlspecialchars($_POST['themes']);
            if (isset($_POST['text']) && !empty($_POST['text'])) {
                $text = htmlspecialchars($_POST['text']);
                if (isset($_POST['lang']) && !empty($_POST['lang'])) {
                    $lang = htmlspecialchars($_POST['lang']);
                    if (isset($_POST['imgURL']))
                        $imgURL = htmlentities($_POST['imgURL']);
                    $content = new News($name, $text, $imgURL);
                    if (isset($_GET['id'])) {
                    } else
                        $insert_stmt = $objPdo->prepare("INSERT INTO `news`(`id_theme`, `content`, `id_redactor`, `language`) VALUES (:theme, :content, :redactor, :lang)");
                    $insert_stmt->bindValue('theme', $theme, PDO::PARAM_INT);
                    $insert_stmt->bindValue('content', json_encode($content), PDO::PARAM_STR);
                    $insert_stmt->bindValue('redactor', 1, PDO::PARAM_INT);
                    $insert_stmt->bindValue('lang', $lang, PDO::PARAM_STR);
                    if (!$insert_stmt->execute()) {
                        $inputErrors['others'] = 'Une erreur MySQL est survenue.';
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
}

//TODO: set fields when editing
if (isset($_GET['id'])) {
}
