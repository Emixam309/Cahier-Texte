<?php include("session.php");
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
<?php include("navbar.php"); ?>
<div class="container">
    <h1>Bienvenue <?php echo $_SESSION['prenom']; ?> !</h1>
    <p>C'est votre tableau d'administration.</p>
    <p>Vous pouvez accéder à differentes pages.</p>
    <a class="btn btn-primary" href="formateurs.php" title="Affiche la liste des formateurs et d'en ajouter de nouveau">Formateurs</a>
    <div class="btn-group">
        <a class="btn btn-primary" href="formations.php"
           title="Affiche la liste des formations et permet d'en ajouter de nouvelle">Formations</a>
        <a class="btn btn-primary" href="promotions.php"
           title="Affiche la liste des promotions et permet d'en ajouter de nouvelle">Promotions</a>
        <a class="btn btn-primary" href="stagiaires.php"
           title="Affiche la liste des stagiaires et permet d'en ajouter de nouveaux">Stagiaires</a>
    </div>
    <div class="btn-group">
        <a class="btn btn-primary" href="modules.php"
           title="Affiche la liste des formations et permet d'en ajouter de nouvelle">Modules</a>
        <a class="btn btn-primary" href="affectation.php"
           title="Affiche la liste des formateurs affectés et permet d'en ajouter de nouveaux">Affectation</a>
    </div>
    <a class="btn btn-primary" href="compte-rendu.php" title="Affiche le compte rendu par formation">Compte rendu</a>
</div>
</body>
</html>