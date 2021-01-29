<?php include("session.php");
if (isset($_POST['compte-rendu'])) {

    $query = 'INSERT INTO compterendu (idModule, idPromo, idUser, date, duree, distanciel, contenu, moyen, objectif, evaluation)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
    $stmt = $bdd->prepare($query);
    $stmt->bind_param("iiisiissss", $_GET['module'], $_GET['promotion'], $_SESSION['idUser'], $_POST['date'], $_POST['duree'],
        $_POST['distanciel'], $_POST['contenu'], $_POST['moyen'], $_POST['objectif'], $_POST['evaluation']);
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
<?php include("navbar.php") ?>
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
            <form class="mb-3" action="" method="get" name="promotions">
                <?php if (isset($_GET['formation'])) {
                echo '<input type="hidden" name="formation" value="' . $_GET['formation'] . '">'; ?>
                <div class="form-floating mb-3">
                    <select class="form-select" name="promotion" onchange="this.form.submit()">
                        <option hidden selected>Sélectionner une promotion</option>
                        <?php //Requete + verification formation sélectionnée
                        $query = $bdd->query('SELECT idPromo, libelle FROM promo WHERE idFormation = ' . $_GET['formation'] . ' AND verrouillage != 1');
                        while ($resultat = $query->fetch_object()) {
                            ;
                            echo '<option value="' . $resultat->idPromo . '"';
                            if (isset($_GET['promotion'])) {
                                if ($_GET['promotion'] == $resultat->idPromo) {
                                    echo 'selected';
                                    $idPromo = $resultat->idPromo;
                                }
                            }
                            echo '>' . $resultat->libelle . '</option>';
                        }
                        $query->close();
                        ?>
                    </select>
                    <label for="formation">Promotion</label>
                </div>
            </form>

            <form class="mb-3" action="" method="get" name="modules">
                <?php }
                if (isset($_GET['promotion'])) {
                    echo '<input type="hidden" name="formation" value="' . $_GET['formation'] . '">';
                    echo '<input type="hidden" name="promotion" value="' . $_GET['promotion'] . '">';
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
                    <input class="form-control" name="date" type="date" required>
                    <label for="date">Date</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" name="duree" type="number" placeholder="Durée">
                    <label for="duree">Durée de la séance</label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="distanciel" name="distanciel" value="1">
                    <label class="form-check-label" for="distanciel">Distanciel</label>
                </div>
        </div>
        <div class="col-xl-4 me-auto">
            <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Contenu de la séance" name="contenu"
                                  style="height: 100px" maxlength="255"></textarea>
                <label for="contenu">Contenu de la séance</label>
            </div>
            <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Moyens techniques et pédagogiques" name="moyen"
                                  style="height: 100px" maxlength="255"></textarea>
                <label for="moyen">Moyens techniques et pédagogiques</label>
            </div>
            <div class="form-floating mb-3">
                <textarea class="form-control" placeholder="Objectif Ciblé" name="objectif" maxlength="255"></textarea>
                <label for="objectif">Objectif ciblé (référentiel)</label>
            </div>
            <div class="form-floating mb-3">
                <textarea class="form-control" placeholder="Evaluation" name="evaluation" maxlength="250"></textarea>
                <label for="evaluation">Evaluation (si il y a, indiquer le libelle)</label>
            </div>
        </div>
        <div class="text-center">
            <input class="btn btn-primary" type="submit" value="Créer" name="compte-rendu">
            <?php } ?>
        </div>
    </div>
</div>
</body>
</html>