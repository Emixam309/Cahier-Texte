<?php include("session.php");

if (isset($_POST['compte-rendu'])) {
    $query = 'INSERT INTO compterendu (idModule, idUser, date, duree, distanciel, contenu, moyen, evaluation)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
    $stmt = $bdd->prepare($query);
    $stmt->bind_param("iisiisss", $_GET['module'], $_SESSION['idUser'], $_POST['date'], $_POST['duree'],
        $_POST['distanciel'], $_POST['contenu'], $_POST['moyen'], $_POST['evaluation']);
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
    <link href="css/bootstrap.css" rel="stylesheet">
    <title>Compte rendu</title>
</head>
<body>
<?php include("header.php") ?>
<div class="container">
    <h1 class="text-center mb-3">Compte Rendu de la séance</h1>
    <div class="row">
        <div class="col-xl-4 ms-auto <?php if (!isset($_GET['module'])) echo 'me-auto' ?>">
            <form class="mb-3" action="" method="get" name="formations">
                <div class="form-floating mb-3">
                    <select class="form-select" name="formation" onchange="this.form.submit()">
                        <option hidden selected>Sélectionner une formation</option>
                        <?php //Requete + verification formation sélectionnée
                        $query = $bdd->query('SELECT formations.idFormation, formations.libelle FROM (formations
                        INNER JOIN modules ON formations.idFormation = modules.idFormation)
                        INNER JOIN formateuraffecte ON modules.idModule = formateuraffecte.idModule
                        WHERE idUser = ' . $_SESSION['idUser'] . ' GROUP BY libelle');
                        while ($resultat = $query->fetch_object()) {
                            echo '<option value="' . $resultat->idFormation . '"';
                            if (isset($_GET['formation'])) {
                                if ($_GET['formation'] == $resultat->idFormation) {
                                    echo 'selected';
                                }
                            }
                            echo '>' . $resultat->libelle . '</option>';
                        }
                        $query->close();
                        ?>
                    </select>
                    <label for="formation">Formation</label>
                </div>
            </form>
            <form class="mb-3" action="" method="get" name="modules">
                <?php if (isset($_GET['formation'])) {
                    echo '<input type="hidden" name="formation" value="' . $_GET['formation'] . '">';
                    /* utilisé pour affecter la valeur de formation avec le nouveau form, et pouvoir reset la valeur de module
                    au changement de formation */ ?>
                    <div class="form-floating">
                        <select class="form-select" name="module" onchange="this.form.submit()">
                            <option hidden selected>Sélectionner un module</option>
                            <?php //Requete + verification module sélectionné
                            $query = $bdd->query('SELECT modules.idModule, libelle FROM formateuraffecte
                            INNER JOIN modules ON modules.idModule = formateuraffecte.idModule
                            WHERE idFormation = ' . $_GET['formation'] . ' AND idUser = ' . $_SESSION['idUser']);
                            while ($resultat = $query->fetch_object()) {
                                ;
                                echo '<option value="' . $resultat->idModule . '"';
                                if (isset($_GET['module']) == $resultat->idModule) {
                                    if ($_GET['module']) {
                                        echo 'selected';
                                    }
                                }
                                echo '>' . $resultat->libelle . '</option>';
                            }
                            $query->close();
                            ?>
                        </select>
                        <label for="module">Module</label>
                    </div>
                <?php } ?>
            </form>
            <form class="" action="" method="post" name="compte-rendu">
                <?php if (isset($_GET['module'])) { ?>
                <div class="form-floating mb-3">
                    <input class="form-control" name="date" type="date">
                    <label for="date">Date</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" name="duree" type="number" placeholder="Durée">
                    <label for="duree">Durée de la séance</label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="distanciel" name="distanciel">
                    <label class="form-check-label" for="distanciel">Distanciel</label>
                </div>
        </div>
        <div class="col-xl-4 me-auto">
            <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Contenu de la séance" name="contenu"
                                  style="height: 100px"></textarea>
                <label for="contenu">Contenu de la séance</label>
            </div>
            <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Moyen" name="moyen"
                                  style="height: 100px"></textarea>
                <label for="moyen">Moyen</label>
            </div>
            <div class="form-floating mb-3">
                <textarea class="form-control" placeholder="Evaluation" name="evaluation"></textarea>
                <label for="evaluation">Evaluation (si il y a, indiquer)</label>
            </div>
        </div>
        <div class="text-center">
            <input class="btn btn-primary" type="submit" value="Créer" name="compte-rendu">
            <?php } ?>
        </div>
        </form>

    </div>
</div>
</div>
</body>
</html>