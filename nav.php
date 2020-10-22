<script src="js/navController.js"></script>

<nav>
    <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="create.php">Creer un article</a></li>
        <li class="dropbtn connect" onclick="showDropdown()"> Dropdown
            <ul class="dropdown-content" id="navdropdown">
                <li><a href="#"><img src="res/fr.png" />Français</a></li>
                <li><a href="#"><img src="res/fr.png" />English</a></li>
            </ul>
        </li>
        <?php
        if (session_id() == "")
            session_start();
        if (isset($_SESSION['login'])) {
            echo '<li class="connect"><a href="#" onclick="unlogAccount()">Déconnexion</a></li>';

            $id = $_SESSION['login'];
            include "models/connect.php";
            $result = $objPdo->prepare("SELECT id_redactor, last_name, first_name FROM redactor WHERE id_redactor = '$id' LIMIT 1");
            $result->execute();
            foreach ($result as $row) {
                echo '<li class="connect"><span>' . $row['last_name'] . ' ' . $row['first_name'] . '</span></li>'; //TODO: link to profile
                break;
            }
        } else { //TODO: not a new page, but an iframe or smthg
            echo '<li class="connect"><a href="php/login.php">Connexion</a></li>';
            echo '<li class="connect"><a href="php/createAccount.php">Créer un compte</a></li>';
        }
        ?>
    </ul>
</nav>