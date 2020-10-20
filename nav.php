<script src="js/navController.js"></script>

<nav>
    <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="create.php">Creer un article</a></li>
        <li class="connect">
            <?php
            if (session_id() == "")
                session_start();
            if (isset($_SESSION['login']))
                echo '<a href="#" onclick="UnlogAccount()">Déconnexion</a>';
            else {
                echo '<a href="php/login.php">Connexion</a></li>';
                echo '<li class="connect"><a href="php/createAccount.php">Créer un compte</a>';
            }
            ?>
        </li>
    </ul>
</nav>