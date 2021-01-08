<?php
// Initialiser la session
session_start();
// Vérifiez si l'utilisateur est connecté et administrateur, sinon redirigez-le vers la page de connexion
if (!isset($_SESSION["username"]) or !isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

require('../config.php');
if (isset($_POST['module'])) {
    $query = 'INSERT INTO formations (libelle,nbHeures,idFormation) VALUES (?, ?, ?)';
    $stmt = $bdd->prepare($query);
    $stmt->bind_param("sss", $_POST['libelle'], $_POST['nbHeures'], $_POST['formation']);
    if (!$stmt->execute()) {
        printf("Erreur : %s\n", $stmt->error);
        $message = "Le formateur n'a pas pu être créé.";
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="../css/bootstrap.css" rel="stylesheet">
    <title>Modules</title>
</head>
<body>
<?php include("header.php"); ?>
<div class="container text-center">
    <h1>Création et affectation de module</h1>
    <form class="container mx-auto" style="width: 50%" action="" method="post" name="module">
        <div class="mb-3">
            <label class="form-label" for="libelle">Nom</label>
            <input class="form-control" name="libelle" type="text" placeholder="Nom du module">
        </div>
        <div class="mb-3">
            <label class="form-label" for="nbHeures">Nombre d'heures</label>
            <div class="mb-3 input-group">
                <input class="form-control" name="nbHeures" type="number" placeholder="Nombre d'heures">
                <span class="input-group-text" id="basic-addon2">heures</span>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="formations">Formation</label>
            <select class="form-select" name="formation">
                <option selected hidden>Affecter à une formation</option>
                <?php
                $query = $bdd->query('SELECT idFormation, libelle FROM formations');
                while ($resultat = $query->fetch_object()) {
                    echo '<option value="' . $resultat->idFormation . '">' . $resultat->libelle . '</option>';
                }
                $query->close();
                ?>
            </select>
        </div>
        <input class="btn btn-primary" type="submit" value="Créer" name="module">
</div>
</body>
</html>