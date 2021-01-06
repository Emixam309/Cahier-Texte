<?php
// Initialiser la session
session_start();
// Vérifiez si l'utilisateur est connecté et administrateur, sinon redirigez-le vers la page de connexion
if (!isset($_SESSION["username"]) or !isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

require('../config.php');
if (isset($_POST['formateur'])) {
    $query = 'INSERT INTO users (nom,prenom,mail,username,password) VALUES (?, ?, ?, ?, ?)';
    $stmt = $bdd->prepare($query);
    $stmt->bind_param("sssss", $_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['id'], $_POST['password']);
    $stmt->execute();
    if (!$stmt->execute()) {
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <title>Formateurs</title>
</head>
<body>
<?php include("header.php"); ?>
<div class="container text-center">
    <h1 class="mb-4">Création de Formateur</h1>
    <form class="mx-auto" style="width: 50%" action="" method="post" name="formateur">
        <div class="row mb-3">
            <div class="col-md">
                <div class="form-floating">
                    <input class="form-control" name="nom" type="text" placeholder="Nom">
                    <label for="nom">Nom</label>
                </div>
            </div>
            <div class="col-md">
                <div class="form-floating">
                    <input class="form-control" name="prenom" type="text" placeholder="Prenom">
                    <label for="prenom">Prenom</label>
                </div>
            </div>
        </div>
        <div class="form-floating mb-3">
            <input class="form-control" name="email" type="email" placeholder="exemple@exemple.com">
            <label for="email">Mail</label>
        </div>
        <div class="form-floating mb-3">
            <input class="form-control" name="id" type="text" placeholder="Identifiant" required>
            <label for="id">Identifiant</label>
        </div>
        <div class="form-floating mb-3">
            <input class="form-control" name="password" type="password" placeholder="Mot de passe" required>
            <label for="password">Mot de passe</label>
        </div>
        <input class="btn btn-primary" type="submit" value="Créer" name="formateur">
        <?php if (!empty($message)) { ?>
            <p class="errorMessage"><?php echo $message; ?></p>
        <?php } ?>
    </form>
</div>
</body>
</html>