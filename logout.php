<?php
    session_start();
    if (isset($_SESSION['isLogged']))
    {
        session_unset();
        session_destroy();
    }
    header('Location:index.php');
