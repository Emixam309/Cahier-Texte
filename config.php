<?php
// Informations d'identification
define('DB_SERVER', 'SERVER');
define('DB_USERNAME', 'USERNAME');
define('DB_PASSWORD', 'PASSWORD');
define('DB_NAME', 'DATABASE');
// Connexion à la base de données MySQL
$bdd = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
// Vérifier la connexion
if ($bdd === false) {
    die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
}
$bdd->set_charset("utf8");