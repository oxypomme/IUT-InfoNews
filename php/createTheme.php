<?php
include_once 'lang/lang.php';
include 'httpRequests.php';

$inputErrors = array(
    'frname' => false,
    'enname' => false,
    'color' => false,
    'iconURL' => false,
    'others' => false
);

$frname = isset($_POST["frname"]) ? htmlentities($_POST['frname']) : '';
$enname = isset($_POST["enname"]) ? htmlentities($_POST['enname']) : '';
$color = isset($_POST["color"]) ? htmlentities($_POST['color']) : '';
$iconURL = isset($_POST["color"]) ? htmlentities($_POST['color']) : '';

function showError($errorName)
{
    if ($GLOBALS['inputErrors'][$errorName])
        echo '<span class="error">' . $GLOBALS['inputErrors'][$errorName] . '</span>';
}

try {
    if (session_id() == "")
        session_start();
    $result_redac = file_get_contents($api_path . '/redactors.php?ID=' . $_SESSION['login']);
    if ($result_redac !== false) {
        $redacs = json_decode($result_redac);
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
    unset($result_redac);
    unset($redacs);
} catch (Exception $e) {
    echo "<script lang=\"javascript\" type=\"text/javascript\">
        alert(\"" . getTrad('baseError') . $e->getMessage() . "\");
        window.location.href = 'index.php';
    </script>";
    exit;
}

if (session_id() == "")
    session_start();
if (isset($_POST['submit'])) {
    if (isset($_POST["frname"]) && !empty($_POST['frname'])) {
        if (isset($_POST['enname']) && !empty($_POST['enname'])) {
            if (isset($_POST['color']) && !empty($_POST['color'])) {
                $label = new stdClass();
                $label->en = $enname;
                $label->fr = $frname;
                $data = array('label' => json_encode($label), 'color' => $color);
                if (isset($_POST['iconURL']))
                    $data['iconURL'] = htmlentities($_POST['iconURL']);
                if (isset($_GET['ID'])) {
                    $data['ID'] = htmlentities($_GET['ID']);
                    $data['method'] = 'UPDATE';
                } else
                    $data['method'] = 'NEW';

                $result = httpRequest('themes.php', $data);

                if ($result == FALSE)
                    $inputErrors['others'] = getTrad('httpError');
                else if (($err = json_decode($result)->sucess) !== true)
                    $inputErrors['others'] = $err;
                else
                    header('Location:index.php');
            } else
                $inputErrors['color'] = getTrad('color');
        } else
            $inputErrors['enname'] = getTrad('nameError');
    } else
        $inputErrors['frname'] = getTrad('nameError');
}

if (isset($_GET['ID'])) {
    $result = file_get_contents($api_path . 'themes.php?ID=' . htmlentities($_GET['ID']));
    if ($result !== false) {
        $themes = json_decode($result);
        if ($themes->sucess) {
            $themes = $themes->themes[0];

            $label = json_decode($themes->label);
            $frname = $label->fr;
            $enname = $label->en;
            $color = $themes->color;
            $iconURL = $themes->iconURL;
        }
    }
}
