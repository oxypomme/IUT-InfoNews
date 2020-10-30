<?php include_once 'lang/lang.php' ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Info News</title>
    <link rel="icon" href="favicon.ico" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" media="screen and (max-width:720px)" href="css/mobile.css" />
    <meta name="viewport" content="initial-scale=1" />
    <script src="js/apiController.js"></script>
    <script src="js/indexController.js"></script>
</head>

<body onLoad="onLoad();">
    <header>
        <?php include "nav.php" ?>
    </header>

    <aside>
        <form id="filters" onchange="onFilterChange();">
            <fieldset>
                <legend><?= getTrad('sort') ?></legend>
                <label class="radio-container">
                    <input type="radio" name="sort" value="desc" checked />
                    <span></span>
                    <?= getTrad('recentFirst') ?>
                </label>
                <label class="radio-container">
                    <input type="radio" name="sort" value="asc" />
                    <span></span>
                    <?= getTrad('oldFirst') ?>
                </label>
            </fieldset>
            <div>
                <div class="select-container">
                    <select name="themes" id="themes" onfocus="onThemeFocus();"></select>
                </div>
                <label for="themes"><?= getTrad('theme') ?></label>
            </div>
            <fieldset>
                <legend><?= getTrad('filter') ?></legend>
                <label class="radio-container">
                    <input type="radio" name="lang" value="" checked />
                    <span></span>
                    <?= getTrad('all') ?>
                </label>
                <label class="radio-container">
                    <input type="radio" name="lang" value="fr" />
                    <span></span>
                    <?= getTrad('fr') ?>
                </label>
                <label class="radio-container">
                    <input type="radio" name="lang" value="en" />
                    <span></span>
                    <?= getTrad('en') ?>
                </label>
            </fieldset>
        </form>
    </aside>

    <main>
        <h1>Info News</h1>
        <div id="detailedNews"></div>
        <section id="news"></section>
    </main>

    <footer>

    </footer>
</body>

</html>