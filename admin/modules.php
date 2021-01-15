<?php include("session.php");
if (isset($_POST['module'])) {
    $query = 'INSERT INTO modules (libelle, reference, nbHeures, commentaire, idFormation) VALUES (?, ?, ?, ?, ?)';
    $stmt = $bdd->prepare($query);
    $stmt->bind_param("ssisi", $_POST['libelle'], $_POST['reference'], $_POST['nbHeures'], $_POST['commentaire'], $_GET['formation']);
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
    <title>Modules</title>
</head>
<body>
<?php include("header.php"); ?>
<div class="container text-center">
    <div class="row"> <!--Tableau de la liste des Formateurs-->
        <div class="col-md-auto mx-auto text-center mb-4"> <!--Formulaire de création d'un formateur-->
            <h1>Création de module</h1>
                <form action="" method="get" name="formation">
                    <div class="mb-3">
                        <div class="form-floating">
                            <select class="form-select" name="formation" onchange="this.form.submit()">
                                <option hidden selected>Sélectionner une formation</option>
                                <?php //Requete + verification formation sélectionnée
                                $query = $bdd->query('SELECT idFormation, libelle, reference FROM formations');
                                while ($resultat = $query->fetch_object()) {
                                    ;
                                    echo '<option value="' . $resultat->idFormation . '"';
                                    if (isset($_GET['formation'])) {
                                        if ($_GET['formation'] == $resultat->idFormation) {
                                            echo 'selected';
                                            $reference = $resultat->reference;
                                        }
                                    }
                                    echo '>' . $resultat->libelle . '</option>';
                                }
                                $query->close();

                                ?>
                            </select>
                            <label for="formation">Formation du module</label>
                        </div>
                    </div>
                </form>
                <form action="" method="post" name="module">
                    <?php if (isset($_GET['formation'])) { ?>
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <div class="form-floating">
                                <input class="form-control" name="reference" type="text" placeholder="Reference" required>
                                <label for="reference">Reference</label>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-floating">
                                <input class="form-control" name="libelle" type="text" placeholder="Nom du module" required>
                                <label for="libelle">Nom du module</label>
                            </div>
                        </div>
                    </div>
                        <div class="mb-3 input-group">
                            <input class="form-control" name="nbHeures" type="number" placeholder="Nombre d'heures">
                            <span class="input-group-text">heures</span>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Laisser un commentaire" name="commentaire"
                                  style="height: 100px"></textarea>
                        <label for="commentaire">Commentaire</label>
                    </div>
                    <input class="btn btn-primary" type="submit" value="Créer" name="module">
                </form>
        </div>
        <div class="col-md-auto mx-auto">
            <h1 class="text-center mb-4">Liste des modules de <?php echo $reference ?></h1>
            <table class="table table-striped border border-3">
                <thead>
                <tr>
                    <th scope="col">Reference</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Nombre d'heures</th>
                    <th scope="col">Commentaire</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $query = $bdd->query('SELECT * FROM modules WHERE idFormation = ' . $_GET['formation']);
                while ($resultat = $query->fetch_object()) {
                    echo "<tr>";
                    echo "<td>" . $resultat->reference . "</td>";
                    echo "<td>" . $resultat->libelle . "</td>";
                    echo "<td>" . $resultat->nbHeures . "</td>";
                    echo "<td>" . $resultat->commentaire . "</td>";
                    echo "</tr>";
                } ?>

                </tbody>
            </table>
        </div>
        <?php } ?>
    </div>
</body>
</html>