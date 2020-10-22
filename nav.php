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
        <li class="dropbtn connect" id="navdropbtn" onclick="showDropdown()"> Langue
            <ul class="dropdown-content" id="navdropdown">
                <li id="nav-fr"><a href="#" onclick="changeLanguage('fr')"><img src="res/fr.png" alt="fr" />Français</a></li>
                <li id="nav-en"><a href="#" onclick="changeLanguage('en')"><img src="res/en.png" alt="en" />English</a></li>
            </ul>
        </li>
    </ul>
</nav>