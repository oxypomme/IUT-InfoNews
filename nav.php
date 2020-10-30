<?php include_once 'lang/lang.php' ?>
<script src="js/navController.js"></script>

<nav>
    <ul>
        <li><a href="index.php"><?= getTrad('home') ?></a></li>
        <?php

        if (session_id() == "")
            session_start();
        if (isset($_SESSION['login'])) {
            $id = $_SESSION['login'];

            echo '<li><a href="news_view.php">Creer un article</a></li>';

            include "api/connect.php";
            $result = $objPdo->prepare("SELECT last_name, first_name, `role` FROM redactor WHERE id_redactor = '$id' LIMIT 1");
            $result->execute();
            foreach ($result as $row) {
                if ($row['role'] == 1)
                    echo '<li><a href="themes_view.php">Creer un theme</a></li>';
                echo '<li class="dropbtn connect dropbtn-min" onclick="showDropdown(this); updateLangDropdown();"> ' . $row['last_name'] . ' ' . $row['first_name'];
                echo '<ul class="dropdown-content">';
                echo '<li><a href="#" onclick="setIframe(\'php/createAccount.php?ID=' . $id . '\', 240)">Profil</a></li>';
                echo '<li><a href="#" onclick="unlogAccount()">Déconnexion</a></li>';
                break;
            }
            echo '</ul>';
            echo '</li>';
        } else {
            echo '<li class="dropbtn connect dropbtn-min" onclick="showDropdown(this); updateLangDropdown();"> Compte';
            echo '<ul class="dropdown-content">';
            echo '<li><a href="#" onclick="setIframe(\'php/login.php\', 160)">Connexion</a></li>';
            echo '<li><a href="#" onclick="setIframe(\'php/createAccount.php\', 240)">Créer un compte</a></li>';
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
    <iframe id="iframeLog" style="display: none;"></iframe>
</nav>