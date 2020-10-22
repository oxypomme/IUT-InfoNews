<?php
$inputErrors = array(
    'lname' => false,
    'fname' => false,
    'login' => false,
    'passwd' => false,
    'others' => false
);

$lname = isset($_POST["lname"]) ? htmlspecialchars($_POST['lname']) : '';
$fname = isset($_POST["fname"]) ? htmlspecialchars($_POST['fname']) : '';
$login = isset($_POST["login"]) ? htmlspecialchars($_POST['login']) : '';
$passwd = isset($_POST["passwd"]) ? htmlspecialchars($_POST['passwd']) : '';

function showError($errorName)
{
    if ($GLOBALS['inputErrors'][$errorName])
        echo '<span class="error">' . $GLOBALS['inputErrors'][$errorName] . '</span>';
}

if (isset($_POST['submit'])) {
    if (isset($_POST["lname"]) && !empty($_POST['lname'])) {
        if (isset($_POST["fname"]) && !empty($_POST['fname'])) {
            if (isset($_POST["login"]) && !empty($_POST['login'])) {
                if (isset($_POST["passwd"]) && !empty($_POST['passwd'])) {
                    $data = array('lname' => $lname, 'fname' => $fname, 'mail' => $login, 'pass' => $passwd);
                    if (isset($_GET['ID'])) {
                        $data['ID'] = htmlspecialchars($_GET['ID']);
                        $data['method'] = 'UPDATE';
                    } else
                        $data['method'] = 'NEW';
                    $path = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['REQUEST_URI'], 2) . '/models/redactors.php';
                    $options = array(
                        'http' => array(
                            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                            'method'  => 'POST',
                            'content' => http_build_query($data)
                        )
                    );
                    $context = stream_context_create($options);
                    $result = file_get_contents($path, false, $context);

                    if ($result === FALSE)
                        $inputErrors['others'] = 'Une erreur HTTP est survenue.';
                    else if (($err = json_decode($result)->sucess) !== true) {
                        $inputErrors['others'] = $err;

                        include "../models/connect.php";
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


//TODO: check if mail is really a mail, etc.
if (isset($_GET['ID'])) {
    if (session_id() == "")
        session_start();
    $path = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['REQUEST_URI'], 2) . '/models/redactors.php?ID=' . $_SESSION['login'];
    $result = file_get_contents($path);
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
</head>

<body class="iframable">
    <form method="post">
        <div>
            <label>Nom :</br>
                <input type="text" name="lname" value=<?php echo '"' . $lname . '"' ?> />
            </label>
            <?php showError("lname") ?>
        </div>
        <div>
            <label>Prénom :</br>
                <input type="text" name="fname" value=<?php echo '"' . $fname . '"' ?> />
            </label>
            <?php showError("fname") ?>
        </div>
        <div>
            <label>Adresse mail :</br>
                <input type="text" name="login" value=<?php echo '"' . $login . '"' ?> />
            </label>
            <?php showError("login") ?>
        </div>
        <div>
            <label>Mot de passe :</br>
                <input type="passsword" name="passwd" value=<?php echo '"' . $passwd . '"' ?> />
            </label>
            <?php showError("passwd") ?>
        </div>
        <?php showError("others") ?><br />

        <input type="submit" name="submit" value="Valider" />
    </form>
</body>

</html>