<script src="js/navController.js"></script>

<nav>
    <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="create.php">Creer un article</a></li>
        <?php
        if (session_id() == "")
            session_start();
        if (isset($_SESSION['login'])) {
            echo '<li class="connect"><a href="#" onclick="unlogAccount()">Déconnexion</a></li>';

            $mail = $_SESSION['login'];
            include "models/connect.php";
            $result = $objPdo->prepare("SELECT last_name, first_name FROM redactor WHERE mail = '$mail'");
            $result->execute();
            foreach ($result as $row) {
                echo '<li class="connect"><span>' . $row['last_name'] . ' ' . $row['first_name'] . '</span></li>'; //TODO: link to profile
            }
        } else { //TODO: not a new page, but an iframe or smthg
            echo '<li class="connect"><a href="php/login.php">Connexion</a></li>';
            echo '<li class="connect"><a href="php/createAccount.php">Créer un compte</a></li>';
        }
        ?>
    </ul>
</nav>=