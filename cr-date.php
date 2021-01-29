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
    <h1 class="text-center mb-4">Compte Rendu par Dates</h1>
    <form class="" action="" method="get">
        <div class="row">
            <div class="col-xl-auto mx-auto mb-3">
                <div class="form-floating mb-3">
                    <input class="form-control" name="date" type="date">
                    <label for="date">Date</label>
                </div>
                <div class="text-center">
                    <input class="btn btn-primary" type="submit">
                </div>
            </div>
            <?php if (isset($_GET['date'])) { ?>
                <div class="col-xl-auto mx-auto">
                    <?php
                    $date = strtotime($_GET['date']);
                    echo '<h2 class="text-center mb-3">Événements du ' . date('d/m/Y', $date) . ' par ' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '</h2>'
                    ?>
                    <table class="table table-striped border border-3 text-center">
                        <thead>
                        <th scope="col">Formation</th>
                        <th scope="col">Module</th>
                        <th scope="col">Durée</th>
                        <th scope="col">Contenu</th>
                        <th scope="col">Moyen</th>
                        <th scope="col">Evaluation</th>
                        <th scope="col">Distanciel</th>
                        </thead>
                        <tbody>
                        <?php
                        $query = $bdd->query('SELECT formations.libelle as fLibelle, modules.libelle as mLibelle, compterendu.duree, contenu, moyen, evaluation, distanciel FROM (compterendu
                        INNER JOIN modules ON modules.idModule = compterendu.idModule)
                        INNER JOIN formations ON formations.idFormation = modules.idFormation
                        WHERE date = "' . $_GET['date'] . '" AND idUser = ' . $_SESSION['idUser'].' GROUP BY fLibelle, mLibelle, dateEntree');
                        while ($resultat = $query->fetch_object()) {
                            echo '<tr>';
                            echo '<td>' . $resultat->fLibelle . '</td>';
                            echo '<td>' . $resultat->mLibelle . '</td>';
                            echo '<td>' . $resultat->duree . 'h</td>';
                            echo '<td>' . $resultat->contenu . '</td>';
                            echo '<td>' . $resultat->moyen . '</td>';
                            echo '<td>' . $resultat->evaluation . '</td>';
                            echo '<td>';
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
    </form>
</div>
</body>
</html>