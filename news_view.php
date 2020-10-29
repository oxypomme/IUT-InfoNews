<!DOCTYPE html>
<html lang="fr">

<?php
if (session_id() == "")
    session_start();
if (!isset($_SESSION['login'])) {
    echo "<script lang=\"javascript\" type=\"text/javascript\">
            alert(\"Vous dezvez être connecté pour accéder à cette page.\");
            window.location.href = 'index.php';
        </script>";
}
include "php/createNews.php";
?>

<head>
    <meta charset="UTF-8">
    <title>Info News - Création d'Article</title>
    <link rel="icon" href="favicon.ico" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" media="screen and (max-width:720px)" href="css/mobile.css" />
    <meta name="viewport" content="initial-scale=1" />
    <script src="js/apiController.js"></script>
    <script lang="javascript" type="text/javascript">
        function getPostedTheme() {
            return <?= (isset($_POST['themes']) ? $_POST['themes'] : $theme) ?>;
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
                <input type="text" name="name" value=<?= '"' . $name . '"' ?> required />
                <?php showError("name") ?>
                <label for="name">Nom*</label>
            </div>
            <div>
                <div class="select-container">
                    <select name="themes" id="themes" onfocus="onLoad();">
                    </select>
                </div>
                <label for="themes">Theme*</label>
                <?php showError("theme") ?>
            </div>
            <div>
                <input type="url" name="imgURL" value=<?= '"' . $imgURL . '"' ?> />
                <label for="imgURL">Image d'en-tête</label>
            </div>
            <div>
                <textarea name="text" rows="4" cols="50" required><?= $text ?></textarea>
                <?php showError("text") ?>
                <label for="text">Texte*</label>
            </div>
            <div>
                <div class="select-container">
                    <select name="lang">
                        <option value="fr" <?= ($lang == 'fr' ? 'selected' : '') ?>>Français</option>
                        <option value="en" <?= ($lang == 'en' ? 'selected' : '') ?>>English</option>
                    </select>
                </div>
                <label for="lang">Langue</label>
                <?php showError("lang") ?>
            </div>
            <?php showError("others") ?><br />

            <input type="submit" name="submit" value="Valider" />
            <input type="submit" name="cancel" value="Annuler" />
        </form>
        Les champs marqués d'un * sont obligatoires.
    </main>

    <footer>

    </footer>
</body>

</html>