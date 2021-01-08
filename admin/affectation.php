<?php
// Initialiser la session
session_start();
// Vérifiez si l'utilisateur est connecté et administrateur, sinon redirigez-le vers la page de connexion
if (!isset($_SESSION["username"]) or !isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}

require('../config.php');
if (isset($_POST['affecter'])) {
    $query = 'INSERT INTO effectuer (idModule,username) VALUES (?, ?)';
    $stmt = $bdd->prepare($query);
    $stmt->bind_param("ss", $_POST['module'], $_POST['formateur']);
    if ($stmt->execute()) {
        $message = "Le formateur à été correctement affecté.";
    } else {
        printf("Erreur : %s\n", $stmt->error);
        $message = "Le formateur n'a pas pu être affecté.";
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
    <title>Affectation</title>
</head>
<body>

<?php include("header.php"); ?>
<div class="container text-center">
    <h1>Affectation</h1>
    <div class="mx-auto mb-3" style="width: 40%">
        <form class="mb-3" action="" method="get" name="formation">
            <div class="mb-3">
                <label class="form-label" for="formations">Formation</label>
                <select class="form-select" name="formations">
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
            <input class="btn btn-secondary" type="submit" value="Choisir">
        </form>
        <form action="" method="post" name="module">
            <?php if (isset($_GET['formations'])) { ?>
            <div class="mb-3">
                <label class="form-label" for="module">Module</label>
                <select class="form-select" name="module">
                    <option selected hidden>Choisir un module</option>
                    <?php
                    $query = $bdd->query('SELECT idModule, libelle FROM modules');
                    while ($resultat = $query->fetch_object()) {
                        echo '<option value="' . $resultat->idModule . '">' . $resultat->libelle . '</option>';
                    }
                    $query->close();
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" for="formateur">Formateur</label>
                <select class="form-select" name="formateur">
                    <option selected hidden>Affecter un formateur</option>
                    <?php
                    $query = $bdd->query('SELECT username, nom, prenom FROM users WHERE admin != 1');
                    while ($resultat = $query->fetch_object()) {
                        echo '<option value="' . $resultat->username . '">' . $resultat->nom . ' ' . $resultat->prenom . '</option>';
                    }
                    $query->close();
                    ?>
                </select>
            </div>
            <input class="btn btn-primary" type="submit" value="Créer" name="affecter">
        <?php } ?>
        </form>
    </div>
    <?php if (!empty($message)) { ?>
        <p><?php echo $message; ?></p>
    <?php } ?>
</div>
</body>
</html>