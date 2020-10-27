<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Info News</title>
    <link rel="icon" href="favicon.ico" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" media="screen and (max-width:720px)" href="css/mobile.css" />
    <script src="js/apiController.js"></script>
    <script src="js/indexController.js"></script>
</head>

<body onLoad="onLoad();">
    <header>
        <?php include "nav.php" ?>
    </header>

    <aside>
        <form id="filters" onchange="onFilterChange();">
            <div>
                Trier par :<br />
                <label class="radio-container">
                    <input type="radio" name="sort" value="desc" checked />
                    <span></span>
                    Plus récent d'abord
                </label>
                <label class="radio-container">
                    <input type="radio" name="sort" value="asc" />
                    <span></span>
                    Plus vieux d'abord
                </label>
            </div>
            <div>
                <label class="select-container">Theme :
                    <div>
                        <select name="themes" id="themes" onfocus="onThemeFocus();"></select>
                    </div>
                </label>
            </div>
            <div>
                Afficher :<br />
                <label class="radio-container">
                    <input type="radio" name="lang" value="" checked />
                    <span></span>
                    Tous
                </label>
                <label class="radio-container">
                    <input type="radio" name="lang" value="fr" />
                    <span></span>
                    Français
                </label>
                <label class="radio-container">
                    <input type="radio" name="lang" value="en" />
                    <span></span>
                    Anglais
                </label>
            </div>
        </form>
    </aside>

    <main>
        <h1>Info News</h1>
        <section id="news"></section>
    </main>

    <footer>

    </footer>
</body>

</html>