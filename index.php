<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Info News</title>
    <link rel="icon" href="favicon.ico" />
    <link rel="stylesheet" href="css/style.css" />
    <script src="js/indexController.js"></script>
</head>

<body onLoad="getAllNews();">
    <header>
        <?php include "nav.php" ?>
    </header>

    <aside>
        <?php include "php/filter.php" ?>
    </aside>

    <main>
        <h1>Info News</h1>
        <section id="news" />
    </main>

    <footer>

    </footer>
</body>

</html>