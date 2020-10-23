<?php
$inputErrors = array(
    'lname' => false,
    'fname' => false,
    'login' => false,
    'passwd' => false,
    'others' => false
);

$lname = isset($_POST["lname"]) ? htmlentities($_POST['lname']) : '';
$fname = isset($_POST["fname"]) ? htmlentities($_POST['fname']) : '';
$login = isset($_POST["login"]) ? htmlentities($_POST['login']) : '';
$passwd = isset($_POST["passwd"]) ? htmlentities($_POST['passwd']) : '';

function showError($errorName)
{
    if ($GLOBALS['inputErrors'][$errorName])
        // echo '<span class="error">' . $GLOBALS['inputErrors'][$errorName] . '</span>';
        echo $GLOBALS['inputErrors'][$errorName];
}

$base_path = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['REQUEST_URI'], 2) . '/api/redactors.php';

if (isset($_POST['submit'])) {
    if (isset($_POST["lname"]) && !empty($_POST['lname'])) {
        if (isset($_POST["fname"]) && !empty($_POST['fname'])) {
            if (isset($_POST["login"]) && !empty($_POST['login'])) {
                if (isset($_POST["passwd"]) && !empty($_POST['passwd'])) {
                    include 'httpRequests.php';

                    $data = array('lname' => $lname, 'fname' => $fname, 'mail' => $login, 'pass' => $passwd);
                    if (isset($_GET['ID'])) {
                        $data['ID'] = htmlentities($_GET['ID']);
                        $data['method'] = 'UPDATE';
                    } else
                        $data['method'] = 'NEW';
                    $result = httpRequest($base_path, $data);

                    if ($result === FALSE)
                        $inputErrors['others'] = 'Une erreur HTTP est survenue.';
                    else if (($err = json_decode($result)->sucess) !== true) {
                        $inputErrors['others'] = $err;

                        include "../api/connect.php";
                        $reslog = $objPdo->prepare("SELECT COUNT(*) AS 'match' FROM redactor WHERE mail = '$login' LIMIT 1");
                        $reslog->execute();
                        foreach ($reslog as $row)
                            if ($row['match'] != 0)
                                $inputErrors['others'] = "L'adresse mail est déjà utilisée.";
                    } else
                        // header('Location:login.php');
                        echo "<script lang=\"javascript\" type=\"text/javascript\">
                        parent.closeIFrame();
                        </script>";
                } else
                    $inputErrors['passwd'] = 'Mot de passe vide ou incorrect';
            } else
                $inputErrors['login'] = 'Mail vide ou incorrect';
        } else
            $inputErrors['fname'] = 'Prénom vide ou incorrect';
    } else
        $inputErrors['lname'] = 'Nom vide ou incorrect';
}

if (isset($_GET['ID'])) {
    if (session_id() == "")
        session_start();
    $result = file_get_contents($base_path . '?ID=' . $_SESSION['login']);
    if ($result !== false) {
        $redactors = json_decode($result);
        if ($redactors->sucess) {
            $redactors = $redactors->redactors[0];
            if (session_id() == "")
                session_start();
            if ($_SESSION['login'] != $redactors->id) { //Maybe useless but it safier
                echo "<script lang=\"javascript\" type=\"text/javascript\">
                    alert(\"Vous n'êtes pas autorisé à modifier le profil d'un autre !\");
                    window.location.href = 'index.php';
                </script>";
            }
            $lname = $redactors->lname;
            $fname = $redactors->fname;
            $login = $redactors->mail;
        }
    }
}

if (session_id() == "")
    session_start();
if (isset($_SESSION["login"]) and !isset($_GET['ID']))
    // header('Location:../index.php');
    echo "<script lang=\"javascript\" type=\"text/javascript\">
    parent.closeIFrame();
    </script>";

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Info redactors - Login</title>
    <link rel="stylesheet" href="../css/style.css" />
    <script src="../js/createAccountController.js"></script>
</head>

<body class="iframable">
    <form name="redactor" method="post" onsubmit="return validate()">
        <div>
            <label>Nom :</br>
                <input type="text" name="lname" value=<?php echo '"' . $lname . '"' ?> />
            </label>
            <span id="lname" class="error"><?php showError("lname") ?></span>
        </div>
        <div>
            <label>Prénom :</br>
                <input type="text" name="fname" value=<?php echo '"' . $fname . '"' ?> />
            </label>
            <span id="fname" class="error"><?php showError("fname") ?></span>
        </div>
        <div>
            <label>Adresse mail :</br>
                <input type="text" name="login" value=<?php echo '"' . $login . '"' ?> />
            </label>
            <span id="login" class="error"><?php showError("login") ?></span>
        </div>
        <div>
            <label>Mot de passe : (6 caractères)</br>
                <input type="password" name="passwd" value=<?php echo '"' . $passwd . '"' ?> onkeyup="checkPassword()" />
            </label>
            <!-- <progrsess id="passwordStrenghBar" max="100" value="0"></progress><span id="passwordStrengh">Faible</span> -->
            <meter id="passwordStrenghBar" min="0" max="100" low="45" high="80" optimum="100" value="0"></meter><span id="passwordStrengh">Faible</span>
            <span id="passwd" class="error"><?php showError("passwd") ?></span>
        </div>
        <span class="error"><?php showError("others") ?></span>

        <input type="submit" name="submit" value="Valider" />
    </form>
</body>

</html>