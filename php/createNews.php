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

function showError($errorName)
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
                    $data = array('theme' => $theme, 'content' => json_encode($content), 'lang' => $lang);
                    if (isset($_GET['id'])) {
                        $data['id'] = htmlspecialchars($_GET['id']);
                        $data['method'] = 'UPDATE';
                    } else {
                        $data['method'] = 'NEW';
                        $data['idredact'] = $_SESSION['login'];
                    }
                    $path = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['REQUEST_URI']) . '/models/news.php';
                    $options = array(
                        'http' => array(
                            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                            'method'  => 'POST',
                            'content' => http_build_query($data)
                        )
                    );
                    $context = stream_context_create($options);
                    $result = file_get_contents($path, false, $context);

                    var_dump($result);

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

//TODO: set fields when editing
if (isset($_GET['id'])) {
}
