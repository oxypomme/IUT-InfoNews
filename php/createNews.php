<?php
if (!is_dir('models/'))
    header('Location:../index.php');

$inputErrors = array(
    'name' => false,
    'theme' => false,
    'text' => false,
    'lang' => false,
    'others' => false
);

$name = isset($_POST["name"]) ? htmlentities($_POST['name']) : '';
$theme = isset($_POST["themes"]) ? htmlentities($_POST['themes']) : '';
$text = isset($_POST["text"]) ? htmlentities($_POST['text']) : '';
$lang = isset($_POST["lang"]) ? htmlentities($_POST['lang']) : (isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'fr');
$imgURL = isset($_POST["imgURL"]) ? htmlentities($_POST['imgURL']) : '';

function showError($errorName)
{
    if ($GLOBALS['inputErrors'][$errorName])
        echo '<span class="error">' . $GLOBALS['inputErrors'][$errorName] . '</span>';
}

include 'models/newsClass.php';

$api_path = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['REQUEST_URI'], 1) . '/api';
$base_path = $api_path . '/news.php';

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
                        $data['ID'] = htmlentities($_GET['ID']);
                        $data['method'] = 'UPDATE';
                    } else {
                        $data['method'] = 'NEW';
                        $data['idredact'] = $_SESSION['login'];
                    }
                    $result = httpRequest($base_path, $data);

                    if ($result === FALSE)
                        $inputErrors['others'] = 'Une erreur HTTP est survenue.';
                    else if (($err = json_decode($result)->sucess) !== true)
                        $inputErrors['others'] = $err;
                    else
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
    $result = file_get_contents($base_path . '?ID=' . htmlentities($_GET['ID']));
    if ($result !== false) {
        $news = json_decode($result);
        if ($news->sucess) {
            $news = $news->news[0];

            try {
                if (session_id() == "")
                    session_start();
                if ($_SESSION['login'] != $news->redactor) {
                    $result = file_get_contents($api_path . '/redactors.php?ID=' . $_SESSION['login']);
                    if ($result !== false) {
                        $redacs = json_decode($result);
                        if ($redacs->sucess) {
                            $redacs = $redacs->redactors[0];
                            if ($redacs->role != 1) {
                                throw new Exception('Permission denied');
                            }
                        } else {
                            throw new Exception($redacs->sucess);
                        }
                    } else {
                        throw new Exception('HTTP error');
                    }
                }
            } catch (Exception $e) {
                echo "<script lang=\"javascript\" type=\"text/javascript\">
                    alert(\"Une erreur est survenue : " . $e->getMessage() . "\");
                    window.location.href = 'index.php';
                </script>";
                exit;
            }

            $theme = $news->theme;
            $content = json_decode($news->content);
            $name = $content->title;
            $text = $content->text;
            $imgURL = $content->imgURL;
            $lang = $news->lang;
        }
    }
}
