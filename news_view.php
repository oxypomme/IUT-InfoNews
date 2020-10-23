<!DOCTYPE html>
<html lang="fr">

<?php
if (session_id() == "")
    session_start();
if (!isset($_SESSION['login'])) {
    header("Location:php/login.php?target=news_view.php");
}
include "php/createNews.php";
?>

<head>
    <meta charset="UTF-8">
    <title>Info News - Création d'Article</title>
    <link rel="icon" href="favicon.ico" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" media="screen and (max-width:720px)" href="css/mobile.css" />
    <script src="js/apiController.js"></script>
    <script lang="javascript" type="text/javascript">
        function getPostedTheme() {
            return <?php echo (isset($_POST['themes']) ? $_POST['themes'] : $theme) ?>;
        }
    </script>
    <script src="js/createNewsController.js"></script>
</head>

<body onLoad="onLoad();">
    <header>
        <?php include "nav.php" ?>
        <h1>Créer un article</h1>
    </header>

    <main>
        <form method="post">
            <div>
                <label>Nom :
                    <input type="text" name="name" value=<?php echo '"' . $name . '"' ?> />
                    <?php showError("name") ?>
                </label>
                <label>Theme :
                    <select name="themes" id="themes" onfocus="onLoad();">
                    </select>
                    <?php showError("theme") ?>
                </label>
                <label>Image d'en-tête :
                    <input type="url" name="imgURL" value=<?php echo '"' . $imgURL . '"' ?> />
                </label>
            </div>
            <div>
                <label>Texte :<br />
                    <textarea name="text" rows="4" cols="50"><?php echo $text ?></textarea>
                    <?php showError("text") ?>
                </label>
                <label>Langue :
                    <select name="lang" value=<?php echo '"' . $lang . '"' ?>>
                        <option value="fr">Français</option>
                        <option value="en">English</option>
                    </select>
                    <?php showError("lang") ?>
                </label>
            </div>
            <?php showError("others") ?><br />

            <input type="submit" name="submit" value="Valider" />
            <input type="submit" name="cancel" value="Annuler" />
        </form>
    </main>

    <footer>

    </footer>
</body>

</html>