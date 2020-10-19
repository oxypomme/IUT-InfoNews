<?php
include "models/connect.php";
session_start();
if (isset($_POST['submit'])) {
    if (isset($_POST["login"]) and isset($_POST["passwd"])) {
        $login = htmlentities($_POST["login"]);
        $pass = htmlentities($_POST["passwd"]);
        $reslog = $objPdo->prepare("SELECT COUNT(*) AS 'match' FROM redactor WHERE 'password' = '$pass' AND mail = '$login' LIMIT 1");
        $reslog->execute();
        foreach ($reslog as $row) {
            echo '<script lang="javascript" type="text/javascript">alert(' . $row['match'] . ')</script>';
            if ($row['match'] != 0) {
                $_SESSION["isLogged"] = true;
                if (isset($_GET['target']))
                    header('Location:' . $_GET["target"]);
                else
                    header('Location:index.php');
            } else
                echo 'Adresse mail ou mot de passe incorect';
        }
    }
}

if (isset($_SESSION["isLogged"]))
    header('Location:index.php');

if (isset($_GET['target']))
    echo 'Pour accèder à cette page il est nécessaire de se connecter avec votre identifiant :';
?>

<form method="post">
    <div>
        <label>Adresse mail :</br>
            <input type="text" name="login" value="" />
        </label>
    </div>
    <div>
        <label>Mot de passe :</br>
            <input type="passsword" name="passwd" value="" />
        </label>
    </div>

    <input type="submit" name="submit" value="Valider" />
</form>