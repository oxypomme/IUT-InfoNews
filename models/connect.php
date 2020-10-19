<?php

try {
    $serializedCreditentials = file_get_contents('creditentials');
    $creditentials = unserialize($serializedCreditentials);

    $objPdo = new PDO('mysql:host=' . $creditentials["server"] . ';port=' . $creditentials["port"] . ';dbname=' . $creditentials["database"], $creditentials["username"], $creditentials["password"]);
    //echo '<span style="display: none;">connexion ok</span>';
} catch (Exception $exception) {
    die($exception->getMessage());
}
