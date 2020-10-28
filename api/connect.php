<?php
try {
    if (file_exists('../config/creditentials'))
        $serializedCreditentials = file_get_contents('../config/creditentials');
    else
        $serializedCreditentials = file_get_contents('config/creditentials');

    $creditentials = unserialize($serializedCreditentials);

    $objPdo = new PDO('mysql:host=' . $creditentials["server"] . ';port=' . $creditentials["port"] . ';dbname=' . $creditentials["database"], $creditentials["username"], $creditentials["password"]);
    unset($serializedCreditentials);
    unset($creditentials);
    //echo '<span style="display: none;">connexion ok</span>';
} catch (Exception $exception) {
    die($exception->getMessage());
}
