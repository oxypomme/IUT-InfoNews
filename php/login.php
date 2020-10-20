<?php
include "../models/connect.php";
session_start();
if (isset($_POST['submit'])) {
    if (isset($_POST["login"]) and isset($_POST["passwd"])) {
        $login = htmlentities($_POST["login"]);
        $pass = crypt(htmlentities($_POST["passwd"]), '$2a$07$usesomesillystringforsalt');
        echo '<span class="error">' . $pass . '</span>';
        $reslog = $objPdo->prepare("SELECT COUNT(*) AS 'match' FROM redactor WHERE mail = '$login' AND passwrd = '$pass' LIMIT 1");
        $reslog->execute();
        foreach ($reslog as $row) {
            if ($row['match'] != 0) {
                $_SESSION["login"] = $login;
                if (isset($_GET['target']))
                    header('Location:../' . $_GET["target"]);
                else
                    header('Location:../index.php');
            } else
                echo '<span class="error">Adresse mail ou mot de passe incorect</span>';
        }
    }
}

if (isset($_SESSION["login"]))
    header('Location:../index.php');

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