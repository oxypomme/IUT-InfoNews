<?php
$inputErrors = array(
    'lname' => false,
    'fname' => false,
    'login' => false,
    'passwd' => false,
    'others' => false
);

$lname = '';
$fname = '';
$login = '';
$passwd = '';

function showError(string $errorName)
{
    if ($GLOBALS['inputErrors'][$errorName])
        echo '<span class="error">' . $GLOBALS['inputErrors'][$errorName] . '</span>';
}

include "../models/connect.php";
if (isset($_POST['submit'])) {
    if (isset($_POST["lname"]) && !empty($_POST['lname'])) {
        $lname = htmlspecialchars($_POST['lname']);
        if (isset($_POST["fname"]) && !empty($_POST['fname'])) {
            $fname = htmlspecialchars($_POST["fname"]);
            if (isset($_POST["login"]) && !empty($_POST['login'])) {
                $login = htmlspecialchars($_POST["login"]);
                if (isset($_POST["passwd"]) && !empty($_POST['passwd'])) {
                    $passwd = htmlspecialchars($_POST["passwd"]);
                    if (isset($_GET['id'])) {
                        $insert_stmt = $objPdo->prepare("UPDATE `redactor` SET `last_name`=:lname, `first_name`=:fname, `mail`=:mail, `passwrd`=:pass WHERE `id_redactor`=:id");
                        $insert_stmt->bindValue('id', htmlspecialchars($_GET['id']), PDO::PARAM_INT);
                    } else
                        $insert_stmt = $objPdo->prepare("INSERT INTO `redactor`(`last_name`, `first_name`, `mail`, `passwd`) VALUES (:lname, :fname, :mail, :pass)");
                    $insert_stmt->bindValue('lname', $lname, PDO::PARAM_STR);
                    $insert_stmt->bindValue('fname', $fname, PDO::PARAM_STR);
                    $insert_stmt->bindValue('mail', $login, PDO::PARAM_STR);
                    $insert_stmt->bindValue('pass', $passwd, PDO::PARAM_STR);
                    if (!$insert_stmt->execute()) {
                        $inputErrors['others'] = 'Une erreur MySQL est survenue.';

                        $reslog = $objPdo->prepare("SELECT COUNT(*) AS 'match' FROM redactor WHERE mail = '$login' LIMIT 1");
                        $reslog->execute();
                        foreach ($reslog as $row)
                            if ($row['match'] != 0)
                                $inputErrors['others'] = "L'adresse mail est déjà utilisée.";
                    } else
                        header('Location:login.php');
                } else
                    $inputErrors['passwd'] = 'Mot de passe vide ou incorrect';
            } else
                $inputErrors['login'] = 'Mail vide ou incorrect';
        } else
            $inputErrors['fname'] = 'Prénom vide ou incorrect';
    } else
        $inputErrors['lname'] = 'Nom vide ou incorrect';
}

//TODO: set fields when editing
//TODO: check if mail is really a mail, etc.

session_start();
if (isset($_SESSION["login"]))
    header('Location:../index.php');
?>

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