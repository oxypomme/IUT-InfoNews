<!DOCTYPE html>
<html lang="fr">

<?php
if (session_id() == "")
    session_start();
if (!isset($_SESSION['login'])) {
    header("Location:php/login.php?target=create.php");
}
include "php/createNews.php";
?>

<head>
    <meta charset="UTF-8">
    <title>Info News - Création</title>
    <link rel="icon" href="favicon.ico" />
    <link rel="stylesheet" href="css/style.css" />
    <script src="js/createNewsController.js"></script>
</head>

<body onLoad="getAllThemes();">
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
                    <select name="themes" id="themes" onfocus="getAllThemes();">
                    </select>
                    <?php showError("theme") ?>
                </label>
                <label>Image d'en-tête :
                    <input type="url" name="imgURL" value=<?php echo '"' . $imgURL . '"' ?> />
                </label>
            </div>
            <div>
                <label>Texte :<br />
                    <textarea name="text" rows="4" cols="50" value=<?php echo '"' . $text . '"' ?>></textarea>
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
        </form>
    </main>

    <footer>

    </footer>
</body>

</html>