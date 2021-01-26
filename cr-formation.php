<?php include("session.php"); ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/bootstrap.css" rel="stylesheet">
    <title>Compte Rendu</title>
</head>
<body>
<?php include("navbar.php") ?>
<div class="container">
    <h1 class="text-center mb-4">Compte Rendu par Formations</h1>
    <form class="mb-3" action="" method="get" name="formations">
        <div class="row">
            <div class="col-xl-auto mx-auto mb-3">
                <div class="form-floating mb-3">
                    <select class="form-select" name="formation" onchange="this.form.submit()">
                        <option hidden selected>Sélectionner une formation</option>
                        <?php //Requete + verification formation sélectionnée
                        $query = $bdd->query('SELECT formations.idFormation, formations.libelle, formations.reference FROM (formations
                        INNER JOIN modules ON formations.idFormation = modules.idFormation)
                        INNER JOIN formateuraffecte ON modules.idModule = formateuraffecte.idModule
                        WHERE idUser = ' . $_SESSION['idUser'] . ' GROUP BY libelle');
                        while ($resultat = $query->fetch_object()) {
                            echo '<option value="' . $resultat->idFormation . '"';
                            if (isset($_GET['formation'])) {
                                if ($_GET['formation'] == $resultat->idFormation) {
                                    $reference = $resultat->reference;
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
            </div>
            <?php if (isset($_GET['formation'])) { ?>
                <div class="col-xl-auto mx-auto">
                    <?php
                    echo '<h2 class="text-center mb-3">Événements de ' . $reference . ' par ' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '</h2>'
                    ?>
                    <table class="table table-striped border border-3 text-center">
                        <thead>
                        <th scope="col">Num. Sem.</th>
                        <th scope="col">Module</th>
                        <th scope="col">Date</th>
                        <th scope="col">Durée</th>
                        <th scope="col">Contenu</th>
                        <th scope="col">Moyen</th>
                        <th scope="col">Evaluation</th>
                        <th scope="col">Distanciel</th>
                        </thead>
                        <tbody>
                        <?php
                        $query = $bdd->query('SELECT modules.libelle as mLibelle, compterendu.duree, date, contenu, moyen, evaluation, distanciel FROM compterendu
                        INNER JOIN modules ON modules.idModule = compterendu.idModule
                        WHERE idFormation = "' . $_GET['formation'] . '" AND idUser = ' . $_SESSION['idUser'] . ' GROUP BY date');
                        while ($resultat = $query->fetch_object()) {
                            $date = strtotime($resultat->date);
                            echo '<tr>';
                            echo '<th scope="row">' . date('W', $date) . '</th>';
                            echo '<td>' . $resultat->mLibelle . '</td>';
                            echo '<td>' . date('d/m/Y', $date) . '</td>';
                            echo '<td>' . $resultat->duree . 'h</td>';
                            echo '<td>' . $resultat->contenu . '</td>';
                            echo '<td>' . $resultat->moyen . '</td>';
                            echo '<td>' . $resultat->evaluation . '</td>';
                            echo '<td class="text-center">';
                            if (!empty($resultat->distanciel)) {
                                echo '✔';
                            }
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
</div>
</form>
</div>
</body>
</html>