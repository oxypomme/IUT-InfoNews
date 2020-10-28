<?php
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

$base_path = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['REQUEST_URI'], 1) . '/api/themes.php';

if (session_id() == "")
    session_start();
if (isset($_POST['submit'])) {
    if (isset($_POST["frname"]) && !empty($_POST['frname'])) {
        if (isset($_POST['enname']) && !empty($_POST['enname'])) {
            if (isset($_POST['color']) && !empty($_POST['color'])) {
                include 'httpRequests.php';

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

                $result = httpRequest($base_path, $data);

                if ($result == FALSE)
                    $inputErrors['others'] = 'Une erreur HTTP est sruvenue.';
                else if (($err = json_decode($result)->sucess) !== true)
                    $inputErrors['others'] = $err;
                else
                    header('Location:index.php');
            } else
                $inputErrors['color'] = "Couleur invalide";
        } else
            $inputErrors['enname'] = "Nom invalide";
    } else
        $inputErrors['frname'] = 'Nom invalide';
}

if (isset($_GET['ID'])) {
    $result = file_get_contents($base_path . '?ID=' . htmlentities($_GET['ID']));
    if ($result !== false) {
        $themes = json_decode($result);
        if ($themes->sucess) {
            $themes = $themes->themes[0];
        }
        //Insert condition
        $label = json_decode($themes->label);
        $frname = $label->fr;
        $enname = $label->en;
        $color = $themes->color;
        $iconURL = $themes->iconURL;
    }
}
