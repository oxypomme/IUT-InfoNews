<?php
include "../api/connect.php";
if (session_id() == "")
    session_start();
if (isset($_POST['submit'])) {
    if (isset($_POST["login"]) and isset($_POST["passwd"])) {
        $login = htmlentities($_POST["login"]);
        $pass = crypt(htmlentities($_POST["passwd"]), '$2a$07$usesomesillystringforsalt');
        $reslog = $objPdo->prepare("SELECT id_redactor, COUNT(*) AS 'match' FROM redactor WHERE mail = '$login' AND passwrd = '$pass' LIMIT 1");
        $reslog->execute();
        foreach ($reslog as $row) {
            if ($row['match'] != 0) {
                $_SESSION["login"] = $row['id_redactor'];
                if (isset($_GET['target'])) {
                    header('Location:../' . $_GET["target"]);
                } else
                    // header('Location:../index.php');
                    echo "<script lang=\"javascript\" type=\"text/javascript\">
                    parent.closeIFrame();
                    </script>";
            } else
                echo '<span class="error">Adresse mail ou mot de passe incorect</span>';
            break;
        }
    }
}

if (isset($_SESSION["login"])) {
    // if (isset($_GET['target']))
    //     header('Location:../' . $_GET['target']);
    // else
    //     header('Location:../index.php');
    echo "<script lang=\"javascript\" type=\"text/javascript\">
    parent.closeIFrame();
    </script>";
}

if (isset($_GET['target']))
    echo 'Pour accèder à cette page il est nécessaire de se connecter avec votre identifiant :';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Info News - Login</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="icon" href="../favicon.ico" />
</head>

<body class="iframable">
    <form method="post">
        <div>
            <input type="text" name="login" value="" required />
            <label for="login">Adresse mail*</label>
        </div>
        <div>
            <input type="password" name="passwd" value="" required />
            <label for="passwd">Mot de passe*</label>
        </div>

        <input type="submit" name="submit" value="Valider" />
    </form>
    Les champs marqués d'un * sont obligatoires.
</body>

</html>