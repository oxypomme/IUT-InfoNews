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

            echo '<li><a href="news_view.php">' . getTrad('createNews') . '</a></li>';

            include "api/connect.php";
            $result = $objPdo->prepare("SELECT last_name, first_name, `role` FROM redactor WHERE id_redactor = '$id' LIMIT 1");
            $result->execute();
            foreach ($result as $row) {
                if ($row['role'] == 1)
                    echo '<li><a href="themes_view.php">' . getTrad('createTheme') . '</a></li>';
                echo '<li class="dropbtn connect dropbtn-min" onclick="showDropdown(this); updateLangDropdown();"> ' . utf8_encode($row['last_name']) . ' ' . utf8_encode($row['first_name']);
                echo '<ul class="dropdown-content">';
                echo '<li><a href="#" onclick="setIframe(\'php/createAccount.php?ID=' . $id . '\', 240)">' . getTrad('profile') . '</a></li>';
                echo '<li><a href="#" onclick="unlogAccount()">' . getTrad('logout') . '</a></li>';
                break;
            }
            echo '</ul>';
            echo '</li>';
        } else {
            echo '<li class="dropbtn connect dropbtn-min" onclick="showDropdown(this); updateLangDropdown();">' . getTrad('account');
            echo '<ul class="dropdown-content">';
            echo '<li><a href="#" onclick="setIframe(\'php/login.php\', 160)">' . getTrad('login') . '</a></li>';
            echo '<li><a href="#" onclick="setIframe(\'php/createAccount.php\', 240)">' . getTrad('createAccount') . '</a></li>';
            echo '</ul>';
            echo '</li>';
        }
        ?>
        <li class="dropbtn connect" onclick="showDropdown(this); updateLangDropdown();"> <?= getTrad('lang') ?>
            <ul class="dropdown-content">
                <li id="nav-fr"><a href="#" onclick="changeLanguage('fr')"><img src="res/fr.png" alt="fr" /><?= getTrad('fr') ?></a></li>
                <li id="nav-en"><a href="#" onclick="changeLanguage('en')"><img src="res/en.png" alt="en" /><?= getTrad('en') ?></a></li>
            </ul>
        </li>
    </ul>
    <iframe id="iframeLog" style="display: none;"></iframe>
</nav>