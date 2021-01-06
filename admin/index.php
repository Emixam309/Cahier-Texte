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
    <title>Administration</title>
</head>
<body>
<?php include("header.php"); ?>
<div class="container">
    <h1>Bienvenue <?php echo $_SESSION['prenom']; ?> !</h1>
    <p>C'est votre tableau d'administration.</p>
</div>
</body>
</html>