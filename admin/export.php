<?php
// Initialiser la session
session_start();
// Vérifiez si l'utilisateur est connecté et administrateur, sinon redirigez-le vers la page de connexion
if (!isset($_SESSION["username"]) or !isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
}
require("../config.php");
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="../css/bootstrap.css" rel="stylesheet">
    <title>Exporter</title>
</head>
<body>
<?php include("header.php"); ?>
<div class="container text-center">
    <h1>Exporter</h1>
    <div class="container mx-auto" style="width: 50%">
        <form class="mb-3" action="" method="get" name="exportby">
            <div class="mb-3">
                <label class="form-label" for="exportby">Exporter le cahier de texte en fonction de :</label>
                <select class="form-select" name="type">
                    <option value="users">Formation</option>
                    <option value="formateurs">Formateur</option>
                    <option value="modules">Module</option>
                </select>
            </div>
            <input class="btn btn-secondary" type="submit" value="Choisir">
        </form>
        <form action="" method="post" name="export">
            <?php
            if (isset($_GET['type'])) {
                $type = $_GET['type'] ?>
                <div class="mb-3">
                    <label class="form-label" for="type">Type</label>
                    <select class="form-select" name="type">
                        <option selected hidden>Type</option>
                        <?php
                        switch ($type) {
                            case 'formations':
                                $query = "idFormation, libelle";
                                break;
                            case 'users':
                                $query = "username, nom, prenom";
                                break;
                            case 'modules':
                                $query = "idModule, libelle";
                                break;
                        }
                        $query = $bdd->query('SELECT ' . $query . ' FROM ' . $_GET['type']);
                        while ($resultat = $query->fetch_object()) {
                            echo '<option value="' . $resultat->idFormation . '">' . $resultat->libelle . '</option>';
                        }
                        $query->close();
                        ?>
                    </select>
                </div>
                <input class="btn btn-primary" type="submit" value="Exporter">
            <?php } ?>
        </form>
    </div>
</div>
</body>
</html>
