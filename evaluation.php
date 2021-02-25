<?php include("session.php"); ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/bootstrap.css" rel="stylesheet">
    <title>Évaluation</title>
</head>
<body>
<?php include("navbar.php"); ?>
<div class="container">
    <h1 class="mb-4 text-center">Évaluation</h1>
    <div class="row">
        <div class="col-md-auto mx-auto text-center"> <!--Formulaire de création d'un stagiaire-->
            <form class="mb-3" action="" method="get" name="formations">
                <div class="form-floating mb-3">
                    <select class="form-select" name="formation" onchange="this.form.submit()">
                        <option hidden selected>Sélectionner une formation</option>
                        <?php //Requete + verification formation sélectionnée
                        $query = $bdd->query('SELECT formations.idFormation, formations.libelle FROM (formations
                        INNER JOIN modules ON formations.idFormation = modules.idFormation)
                        INNER JOIN affectation ON modules.idModule = affectation.idModule
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
                        $query = $bdd->query('SELECT idPromo, libelle FROM promotions WHERE idFormation = ' . $_GET['formation'] . ' AND verrouillage != 1');
                        while ($resultat = $query->fetch_object()) {
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
                <?php if (isset($_GET['promotion'])) {
                echo '<input type="hidden" name="formation" value="' . $_GET['formation'] . '">';
                echo '<input type="hidden" name="promotion" value="' . $_GET['promotion'] . '">';
                /* utilisé pour affecter la valeur de formation avec le nouveau form, et pouvoir reset la valeur de module
                au changement de formation */ ?>
                <div class="form-floating mb-3">
                    <select class="form-select" name="module" onchange="this.form.submit()">
                        <option hidden selected>Sélectionner un module</option>
                        <?php //Requete + verification module sélectionné
                        $query = $bdd->query('SELECT modules.idModule, libelle FROM affectation
                            INNER JOIN modules ON modules.idModule = affectation.idModule
                            WHERE idFormation = ' . $_GET['formation'] . ' AND idUser = ' . $_SESSION['idUser'] . ' GROUP BY modules.idModule');
                        while ($resultat = $query->fetch_object()) {
                            echo '<option value="' . $resultat->idModule . '"';
                            if (isset($_GET['module'])) {
                                if ($_GET['module'] == $resultat->idModule) {
                                    $libelle = $resultat->libelle;
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
            </form>
            <?php } if (isset($_GET['module'])) { ?>
            <form action="export-eval.php" method="post">
                <div class="form-floating mb-3">
                    <input class="form-control" name="nomEval"
                           type="text" placeholder="Nom de l'évaluation" required>
                    <label for="nomEval">Nom de l'évaluation</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" name="date"
                           type="date" required>
                    <label for="date">Date</label>
                </div>
        </div>
        <div class="col-md-auto mx-auto text-center">
            <!--Tableau de la liste des Stagiaires-->
            <?php ob_start() ?>
            <table class="table table-striped border border-3">
                <thead>
                <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Prenom</th>
                    <th scope="col">Note</th>
                    <th scope="col">Commentaire</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $query = $bdd->query('SELECT stagiaires.idStagiaire, nom, prenom FROM stagiaires
                    INNER JOIN affectation on affectation.idPromo = stagiaires.idPromo
                    WHERE stagiaires.idPromo = ' . $idPromo . ' GROUP BY nom, prenom, idStagiaire');
                while ($resultat = $query->fetch_object()) {
                    echo "<tr>";
                    echo "<td>" . $resultat->nom . "</td>";
                    echo "<td>" . $resultat->prenom . "</td>"; ?>
                    <td>
                        <select name="note-<?php echo $resultat->idStagiaire ?>">
                            <option>Non Évalué</option>
                            <option>Non Acquis</option>
                            <option>En Cours</option>
                            <option>Acquis</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" maxlength="255" name="commentaire-<?php echo $resultat->idStagiaire ?>">
                    </td>
                <?php }
                $query->close(); ?>
                </tbody>
            </table>
            <input type="hidden" value="<?php echo $libelle ?>" name="libelle">
            <input type="hidden" value="<?php echo $idPromo ?>" name="promotion">
            <input class="btn btn-primary" type="submit" value="Exporter en PDF">
            </form>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
</div>
</body>
</html>