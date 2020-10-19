<script src="js/navController.js"></script>

<nav>
    <ul>
        <li>
            <?php
            session_start();
            if (isset($_SESSION['login']))
                echo '<a href="#" onclick="UnlogAccount()">Déconnexion</a>';
            else {
                echo '<a href="php/login.php">Connexion</a></li>';
                echo '<li><a href="php/createAccount.php">Créer un compte</a>';
            }
            ?>
        </li>
    </ul>
</nav>