<?php
// Initialiser la session
session_start();
// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
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
    <link href="css/bootstrap.css" rel="stylesheet">
    <title>Accueil</title>
</head>
<body>
<?php include("header.php"); ?>
<div class="container">
    <h1>Bienvenue <?php echo $_SESSION['prenom']; ?> !</h1>
    <p>C'est votre tableau de bord.</p>
    <div>

    </div>
</div>
</body>
</html>