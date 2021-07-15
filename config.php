<?php
// Informations d'identification
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'id16032922_admin');
define('DB_PASSWORD', 'frkMCH6aK@fSn84B');
define('DB_NAME', 'id16032922_cahiertexte');

// Connexion à la base de données MySQL
$bdd = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
// Vérifier la connexion
if ($bdd === false) {
    die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
}
$bdd->set_charset("utf8");