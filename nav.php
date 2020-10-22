<script src="js/navController.js"></script>

<nav>
    <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="create.php">Creer un article</a></li>
        <?php
        if (session_id() == "")
            session_start();
        if (isset($_SESSION['login'])) {
            $id = $_SESSION['login'];
            include "models/connect.php";
            $result = $objPdo->prepare("SELECT id_redactor, last_name, first_name FROM redactor WHERE id_redactor = '$id' LIMIT 1");
            $result->execute();
            foreach ($result as $row) {
                echo '<li class="dropbtn connect" onclick="showDropdown(this); updateLangDropdown();"> ' . $row['last_name'] . ' ' . $row['first_name'];
                echo '<ul class="dropdown-content">';
                echo '<li style="cursor: default;"><span>Profil</span></li>'; //TODO: link to profile
                echo '<li><a href="#" onclick="unlogAccount()">Déconnexion</a></li>';
                break;
            }
            echo '</ul>';
            echo '</li>';
        } else { //TODO: not a new page, but an iframe or smthg
            echo '<li class="dropbtn connect" onclick="showDropdown(this); updateLangDropdown();"> Compte';
            echo '<ul class="dropdown-content">';
            echo '<li><a href="php/login.php">Connexion</a></li>';
            echo '<li><a href="php/createAccount.php">Créer un compte</a></li>';
            echo '</ul>';
            echo '</li>';
        }
        ?>
        <li class="dropbtn connect" onclick="showDropdown(this); updateLangDropdown();"> Langue
            <ul class="dropdown-content">
                <li id="nav-fr"><a href="#" onclick="changeLanguage('fr')"><img src="res/fr.png" alt="fr" />Français</a></li>
                <li id="nav-en"><a href="#" onclick="changeLanguage('en')"><img src="res/en.png" alt="en" />English</a></li>
            </ul>
        </li>
    </ul>
</nav>