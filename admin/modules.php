<?php
// Initialiser la session
session_start();
// Vérifiez si l'utilisateur est connecté et administrateur, sinon redirigez-le vers la page de connexion
if (!isset($_SESSION["username"]) or !isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

require('../config.php');

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <title>Modules</title>
</head>
<body>
<?php include("header.php"); ?>
<div class="container text-center">
    <h1>Création et affectation de module</h1>
    <form class="container mx-auto" style="width: 50%" action="" method="post" name="module">
        <div class="mb-3">
            <label class="form-label" for="label">Nom</label>
            <input class="form-control" name="label" type="text" placeholder="Nom du module">
        </div>
        <div class="mb-3">
            <label class="form-label" for="nbHeure">Nombre d'heures</label>
            <div class="mb-3 input-group">
                <input class="form-control" name="nbHeure" type="number" placeholder="Nombre d'heures">
                <span class="input-group-text" id="basic-addon2">heures</span>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="formations">Formation</label>
            <select class="form-select" name="formation">
                <option selected hidden>Choisir une formation</option>
                <?php
                $query = $bdd->query('SELECT idFormation, libelle FROM formations');
                while ($resultat = $query->fetch_object()) {
                    echo '<option value="' . $resultat->idFormation . '">' . $resultat->libelle . '</option>';
                }
                $query->close();
                ?>
            </select>
        </div>
        <input class="btn btn-primary" type="submit" value="Créer" name="formation">
</div>
</body>
</html>