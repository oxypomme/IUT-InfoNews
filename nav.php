<script src="js/navController.js"></script>

<nav>
    <ul>
        <li>
            <?php
            session_start();
            if (isset($_SESSION['login']))
                echo '<a href="#" onclick="UnlogAccount()">Log Out</a>';
            else
                echo '<a href="login.php">Log In</a>';
            ?>
        </li>
    </ul>
</nav>