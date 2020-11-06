<?php
include_once 'lang/lang.php';
if (!is_dir('models/'))
    header('Location:../index.php');

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
$lang = isset($_POST["lang"]) ? htmlspecialchars($_POST['lang']) : (isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'fr');
$imgURL = isset($_POST["imgURL"]) ? htmlspecialchars($_POST['imgURL']) : '';

function showError($errorName)
{
    if ($GLOBALS['inputErrors'][$errorName])
        echo '<span class="error">' . $GLOBALS['inputErrors'][$errorName] . '</span>';
}

include 'models/newsClass.php';
include 'httpRequests.php';

if (session_id() == "")
    session_start();
if (isset($_POST['submit'])) {
    if (isset($_POST["name"]) && !empty($_POST['name'])) {
        if (isset($_POST['themes']) && !empty($_POST['themes'])) {
            if (isset($_POST['text']) && !empty($_POST['text'])) {
                if (isset($_POST['lang']) && !empty($_POST['lang'])) {
                    $content = new News($name, $text, $imgURL);
                    $data = array('theme' => $theme, 'content' => json_encode($content), 'lang' => $lang);
                    if (isset($_GET['ID'])) {
                        $data['ID'] = htmlspecialchars($_GET['ID']);
                        $data['method'] = 'UPDATE';
                    } else {
                        $data['method'] = 'NEW';
                        $data['idredact'] = $_SESSION['login'];
                    }
                    $result = httpRequest('news.php', $data);

                    if ($result === FALSE)
                        $inputErrors['others'] = getTrad('httpError');
                    else if (($err = json_decode($result)->sucess) !== true)
                        $inputErrors['others'] = $err;
                    else
                        header('Location:index.php');
                } else
                    $inputErrors['lang'] = getTrad('langError');
            } else
                $inputErrors['text'] = getTrad('textError');
        } else
            $inputErrors['theme'] = getTrad('themeError');
    } else
        $inputErrors['name'] = getTrad('nameError');
} else if (isset($_POST['cancel'])) {
    header('Location:index.php');
}

if (isset($_GET['ID'])) {
    $result = file_get_contents($api_path . 'news.php' . '?ID=' . htmlspecialchars($_GET['ID']));
    if ($result !== false) {
        $news = json_decode($result);
        if ($news->sucess) {
            $news = $news->news[0];

            try {
                if (session_id() == "")
                    session_start();
                if ($_SESSION['login'] != $news->redactor) {
                    $result = file_get_contents($api_path . 'redactors.php?ID=' . $_SESSION['login']);
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
                    alert(\"" . getTrad('baseError') . $e->getMessage() . "\");
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
