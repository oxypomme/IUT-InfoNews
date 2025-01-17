<?php include_once 'lang/lang.php' ?>
<!DOCTYPE html>
<html lang="fr">

<?php
if (session_id() == "")
    session_start();
if (!isset($_SESSION['login'])) {
    echo "<script lang=\"javascript\" type=\"text/javascript\">
            alert(\"Vous devez être connecté pour accéder à cette page.\");
            window.location.href = 'index.php';
        </script>";
}
include "php/createTheme.php";
?>

<head>
    <meta charset="UTF-8">
    <title>Info News - <?= getTrad('createTheme') ?></title>
    <link rel="icon" href="favicon.ico" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" media="screen and (max-width:720px)" href="css/mobile.css" />
    <meta name="viewport" content="initial-scale=1" />
    <script src="js/apiController.js"></script>
</head>

<body>
    <header>
        <?php include "nav.php" ?>
        <h1><?= getTrad('createTheme') ?></h1>
    </header>

    <main>
        <form method="post">
            <fieldset>
                <legend><?= getTrad('names') ?></legend>
                <div>
                    <input type="text" name="frname" value=<?= '"' . $frname . '"' ?> required />
                    <label><?= getTrad('fr') ?>*</label>
                    <?php showError("frname") ?>
                </div>
                <div>
                    <input type="text" name="enname" value=<?= '"' . $enname . '"' ?> required />
                    <label><?= getTrad('en') ?>*</label>
                    <?php showError("enname") ?>
                </div>
            </fieldset>
            <div>
                <div class="color-container">
                    <input type="color" name="color" value=<?= '"' . ($color ? $color : '#000000') . '"' ?> />
                </div>
                <label for="color"><?= getTrad('color') ?></label>
                <?php showError("color") ?>
            </div>
            <div>
                <input type="url" name="iconURL" value=<?= '"' . $iconURL . '"' ?> required />
                <label for="iconURL"><?= getTrad('icon') ?>*</label>
                <?php showError("iconURL") ?>
            </div>
            <?php showError("others") ?><br />

            <input type="submit" name="submit" value="<?= getTrad('validate') ?>" />
            <input type="button" value="<?= getTrad('cancel') ?>" onclick="history.back();" />
        </form>
        <?= getTrad('requiredFieldText') ?>
    </main>

    <footer>

    </footer>
</body>

</html>