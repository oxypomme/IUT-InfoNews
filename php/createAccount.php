<?php
include_once '../lang/lang.php';
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
    echo '<span id="' . $errorName . '" class="error">' . ($GLOBALS['inputErrors'][$errorName] ? $GLOBALS['inputErrors'][$errorName] : "") . '</span>';
}

include 'httpRequests.php';
if (isset($_POST['submit'])) {
    if (isset($_POST["lname"]) && !empty($_POST['lname']) && preg_match("/^.{3,}$/", $_POST['lname'])) {
        if (isset($_POST["fname"]) && !empty($_POST['fname']) && preg_match("/^.{3,}$/", $_POST['fname'])) {
            if (isset($_POST["login"]) && !empty($_POST['login']) && preg_match("/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+\.([a-zA-Z0-9-]+){2,4}$/", $_POST['login'])) {
                if (isset($_POST["passwd"]) && !empty($_POST['passwd']) && preg_match("/^.{6,}$/", $_POST['passwd'])) {
                    $data = array('lname' => $lname, 'fname' => $fname, 'mail' => $login, 'pass' => $passwd);
                    if (isset($_GET['ID'])) {
                        $data['ID'] = htmlentities($_GET['ID']);
                        $data['method'] = 'UPDATE';
                    } else
                        $data['method'] = 'NEW';
                    $result = httpRequest('redactors.php', $data);

                    if ($result === FALSE)
                        $inputErrors['others'] = getTrad('httpError');
                    else if (($err = json_decode($result)->sucess) !== true) {
                        $inputErrors['others'] = $err;

                        include "../api/connect.php";
                        $reslog = $objPdo->prepare("SELECT COUNT(*) AS 'match' FROM redactor WHERE mail = '$login' LIMIT 1");
                        $reslog->execute();
                        foreach ($reslog as $row)
                            if ($row['match'] != 0)
                                $inputErrors['others'] = getTrad('loginInUse');
                    } else
                        // header('Location:login.php');
                        echo "<script lang=\"javascript\" type=\"text/javascript\">
                        parent.document.location.reload();
                        </script>";
                } else
                    $inputErrors['passwd'] = getTrad('passwdError');
            } else
                $inputErrors['login'] = getTrad('loginError');
        } else
            $inputErrors['fname'] = getTrad('fnameError');
    } else
        $inputErrors['lname'] = getTrad('lnameError');
}

if (isset($_GET['ID'])) {
    if (session_id() == "")
        session_start();
    $result = file_get_contents($api_path . 'redactors.php' . '?ID=' . $_SESSION['login']);
    if ($result !== false) {
        $redactors = json_decode($result);
        if ($redactors->sucess) {
            $redactors = $redactors->redactors[0];
            if (session_id() == "")
                session_start();
            if ($_SESSION['login'] != $redactors->id) { //Maybe useless but it safier
                echo "<script lang=\"javascript\" type=\"text/javascript\">
                    alert(\"Permission denied\");
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
    parent.document.location.reload();
    </script>";

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Info redactors - Login</title>
    <link rel="stylesheet" href="../css/style.css" />
    <script src="../js/apiController.js"></script>
    <script src="../js/createAccountController.js"></script>
</head>

<body class="iframable">
    <form name="redactor" method="post" onsubmit="return validate();">
        <div>
            <input type="text" name="lname" value=<?= '"' . $lname . '"' ?> required />
            <label for="lname"><?= getTrad('lname') ?>*</label>
            <?php showError("lname") ?>
        </div>
        <div>
            <input type="text" name="fname" value=<?= '"' . $fname . '"' ?> required />
            <label for="fname"><?= getTrad('fname') ?>*</label>
            <?php showError("fname") ?>
        </div>
        <div>
            <input type="text" name="login" value=<?= '"' . $login . '"' ?> required />
            <label for="login"><?= getTrad('mail') ?>*</label>
            <?php showError("login") ?>
        </div>
        <div>
            <input type="password" name="passwd" value=<?php echo '"' . $passwd . '"' ?> onkeyup="checkPassword()" placeholder="<?= getTrad('characNeeded') ?>" required />
            <label id="createPass" for="passwd"><?= getTrad('passwd') ?>*</label>
            <meter id="passwordStrenghBar" min="0" max="100" low="45" high="80" optimum="100" value="0"></meter><span id="passwordStrengh"><?= getTrad('weak') ?></span>
            <?php showError("passwd") ?>
        </div>
        <?php showError("others") ?>

        <input type="submit" name="submit" value="<?= getTrad('validate') ?>" />
    </form>
    <?= getTrad('requiredFieldText') ?>
</body>

</html>