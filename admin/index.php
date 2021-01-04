<?php
// Initialiser la session
session_start();
// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
if(!isset($_SESSION["username"]) or $_SESSION['administrateur'] != 1){
    header("Location: ../login.php");
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
    <title>Administration</title>
</head>
<body>
<div class="sucess">
    <h1>Bienvenue <?php echo $_SESSION['prenom']; ?> !</h1>
    <p>C'est votre tableau d'administration.</p>
    <a href="formation.php">Formations</a>
    <a href="../logout.php">Déconnexion</a>
</div>
</body>
</html>