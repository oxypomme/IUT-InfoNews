<!DOCTYPE html>
<html lang="fr">

<?php
if (session_id() == "")
    session_start();
if (!isset($_SESSION['login'])) {
    header("Location:php/login.php?target=themes_view.php");
}
include "php/createTheme.php";
?>

<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="icon" href="favicon.ico" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" media="screen and (max-width:720px)" href="css/mobile.css" />
</head>

<body>
    <header>
        <?php include "nav.php" ?>
        <h1>Créer un thème</h1>
    </header>

    <main>
        <form method="post">
            <div>Nom :</br>
                <label>Français :
                    <input type="text" name="frname" value=<?php echo '"' . $frname . '"' ?> />
                </label>
                <?php showError("frname") ?>
                <label>Anglais :
                    <input type="text" name="enname" value=<?php echo '"' . $enname . '"' ?> />
                </label>
                <?php showError("enname") ?>
            </div>
            <div>
                <label>Couleur :
                    <input type="color" name="color" value=<?php echo '"' . $color . '"' ?> />
                </label>
                <?php showError("color") ?>
            </div>
            <div>
                <label>Icone :</br>
                    <input type="url" name="iconURL" value=<?php echo '"' . $iconURL . '"' ?> />
                </label>
                <?php showError("iconURL") ?>
            </div>
            <?php showError("others") ?><br />

            <input type="submit" name="submit" value="Valider" />
        </form>
    </main>

    <footer>

    </footer>
</body>

</html>