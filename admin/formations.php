<?php
// Initialiser la session
session_start();
// Vérifiez si l'utilisateur est connecté et administrateur, sinon redirigez-le vers la page de connexion
if (!isset($_SESSION["username"]) or !isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}
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
    <title>Document</title>
</head>
<body>
<?php include("header.php"); ?>
<div class="container text-center">
    <h1>Création de formations</h1>
    <form class="mx-auto" style="width: 60%" action="" method="post" name="formation">
        <div class="mb-3 row">
            <label for="label" class="col-sm-2 col-form-label text-end">Nom</label>
            <div class="col-sm-6">
                <input class="form-control" name="label" type="text" placeholder="Nom de la formation">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="dateDebut" class="col-sm-2 col-form-label text-end">Date de début</label>
            <div class="col-sm-3">
                <input class="form-control" name="dateDebut" type="date">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="dateFin" class="col-sm-2 col-form-label text-end">Date de fin</label>
            <div class="col-sm-3">
                <input class="form-control" name="dateFin" type="date">
            </div>
        </div>
        <input class="btn btn-primary " type="submit" value="Créer" name="formation">
    </form>
</div>
</body>
</html>
