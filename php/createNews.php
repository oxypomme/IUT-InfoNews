<?php
$inputErrors = array(
    'name' => false,
    'theme' => false,
    'text' => false,
    'lang' => false,
    'others' => false
);

$name = '';
$theme = '';
$text = '';
$lang = '';

function showError(string $errorName)
{
    if ($GLOBALS['inputErrors'][$errorName])
        echo '<span class="error">' . $GLOBALS['inputErrors'][$errorName] . '</span>';
}

include '../models/connect.php';
include '../models/newsClass.php';
if (isset($_POST['submit'])) {
    if (isset($_POST["name"]) && !empty($_POST['name'])) {
        $name = htmlspecialchars($_POST['name']);
        if (isset($_POST['themes']) && !empty($_POST['themes'])) {
            $theme = htmlspecialchars($_POST['themes']);
            if (isset($_POST['text']) && !empty($_POST['text'])) {
                $text = htmlspecialchars($_POST['text']);
                if (isset($_POST['lang']) && !empty($_POST['lang'])) {
                    $lang = htmlspecialchars($_POST['lang']);
                    $content = new News($name, $text, (isset($_POST['imgURL']) ? htmlentities($_POST['imgURL']) : ''));
                    if (isset($_GET['id'])) {
                    } else
                        $insert_stmt = $objPdo->prepare("INSERT INTO `news`(`id_theme`, `content`, `id_redactor`, `language`) VALUES (:theme, :content, :redactor, :lang)");
                    $insert_stmt->bindValue('theme', $theme, PDO::PARAM_INT);
                    $insert_stmt->bindValue('content', json_encode($content), PDO::PARAM_STR);
                    //$insert_stmt->bindValue('redactor', ..., PDO::PARAM_INT);
                    $insert_stmt->bindValue('redactor', 1, PDO::PARAM_INT);
                    $insert_stmt->bindValue('lang', $lang, PDO::PARAM_STR);
                    if (!$insert_stmt->execute()) {
                        $inputErrors['others'] = 'Une erreur MySQL est survenue.';
                    }
                }
            }
        }
    }
}

//TODO: set fields when editing
if (isset($_GET['id'])) {
}

?>

<script src="../js/createNewsController.js"></script>

<form method="post">
    <div>
        <label>Nom :<br />
            <input type="text" name="name" />
        </label> <?php showError("name") ?><br />
        <label>Theme :<br />
            <select name="themes" id="themes" onfocus="getAllThemes();">
                <option></option>
            </select>
        </label> <?php showError("theme") ?><br />
        <label>Image d'en-tête :<br />
            <input type="url" name="imgURL" />
        </label>
    </div>
    <div>
        <label>Texte :<br />
            <textarea name="text" rows="4" cols="50"></textarea>
        </label>
        <?php showError("text") ?><br />
    </div>
    <div>
        <label>Langue :<br />
            <select name="lang">
                <option value="fr">Français</option>
                <option value="en">English</option>
            </select>
        </label>
        <?php showError("lang") ?><br />
    </div>
    <?php showError("others") ?><br />

    <input type="submit" name="submit" value="Valider" />
</form>