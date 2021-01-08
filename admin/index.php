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
    <link href="../css/bootstrap.css" rel="stylesheet">
    <title>Administration</title>
</head>
<body>
<?php include("header.php"); ?>
<div class="container">
    <h1>Bienvenue <?php echo $_SESSION['prenom']; ?> !</h1>
    <p>C'est votre tableau d'administration.</p>
    <p>Vous pouvez acceder a differente pages.</p>
    <p><a class="btn btn-primary" href="formateurs.php">Formateurs</a> permet d'afficher la liste des formateurs et d'en ajouter de nouveau.</p>
    <p><a>Formations</a> affiche la liste des formations et d'en ajouter de nouvelle.</p>
</div>
</body>
</html>