<?php include("session.php");

if (isset($_POST['formation'])) {
    $query = 'INSERT INTO formations (libelle, reference, duree) VALUES (?, ?, ?)';
    $stmt = $bdd->prepare($query);
    $stmt->bind_param("sss", $_POST['libelle'], $_POST['reference'], $_POST['duree']);
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
    <title>Formations</title>
</head>
<body>
<?php include("header.php"); ?>
<div class="container">
    <div class="row">
        <div class="col-xl-auto mx-auto text-center"> <!--Formulaire de création d'une formation-->
            <h1 class="px-5">Création de formations</h1>
            <form action="" method="post" name="formation">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input class="form-control" name="reference" type="text" placeholder="Reference" required>
                            <label for="reference">Reference</label>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-floating">
                            <input class="form-control" name="libelle" type="text" placeholder="Nom de la formation" required>
                            <label for="libelle">Nom de la formation</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3 input-group">
                    <input class="form-control" name="duree" type="number" placeholder="Durée de la formation en mois">
                    <span class="input-group-text">mois</span>
                </div>
                <input class="btn btn-primary" type="submit" value="Ajouter" name="formation">
            </form>
        </div>
        <div class="col-xl-auto mx-auto mb-3"> <!--Tableau de la liste des Formations-->
            <h1 class="text-center mb-4">Liste des formations</h1>
            <table class="table table-striped border border-3">
                <thead>
                <tr>
                    <th scope="col">Reference</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Durée</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $query = $bdd->query('SELECT * FROM formations');
                while ($resultat = $query->fetch_object()) {
                    echo "<tr>";
                    echo "<td>" . $resultat->reference . "</td>";
                    echo "<td>" . $resultat->libelle . "</td>";
                    echo "<td>" . $resultat->duree . " mois</td>";
                    echo "</tr>";
                } ?>

                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
