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
            <div class="col-sm-auto mx-auto mb-3">
                <div class="form-floating mb-3">
                    <input class="form-control" name="date" type="date">
                    <label for="date">Date</label>
                </div>
                <div class="text-center">
                    <input class="btn btn-primary" type="submit">
                </div>
            </div>
    </form>
    <?php if (isset($_GET['date'])) { ?>
        <div class="col-xl-auto mx-auto">
            <?php
            $date = strtotime($_GET['date']);
            echo '<h2 class="text-center mb-3">Événements du ' . date('d/m/Y', $date) . ' par ' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '</h2>'
            ?>
            <table class="table table-striped border border-3 text-center">
                <thead>
                <tr>
                    <th scope="col">Promotion</th>
                    <th scope="col">Module</th>
                    <th scope="col">Durée</th>
                    <th scope="col">Contenu</th>
                    <th scope="col">Moyen</th>
                    <th scope="col">Evaluation</th>
                    <th scope="col">Distanciel</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $query = $bdd->query('SELECT idCompteRendu, promotions.idPromo, formations.idFormation, modules.idModule, formations.libelle as fLibelle, modules.libelle as mLibelle, promotions.libelle as pLibelle,
                        compterendu.duree, contenu, moyen, evaluation, distanciel FROM ((compterendu
                        INNER JOIN modules ON modules.idModule = compterendu.idModule)
                        INNER JOIN formations ON formations.idFormation = modules.idFormation)
                        INNER JOIN promotions ON promotions.idPromo = compterendu.idPromo
                        WHERE date = "' . $_GET['date'] . '" AND idUser = ' . $_SESSION['idUser'] . ' GROUP BY fLibelle, mLibelle, dateEntree');
                while ($resultat = $query->fetch_object()) { ?>
                    <tr>
                        <td><?php echo $resultat->pLibelle ?></td>
                        <td><?php echo $resultat->mLibelle ?></td>
                        <td><?php echo $resultat->duree ?>h</td>
                        <td><?php echo $resultat->contenu ?></td>
                        <td><?php echo $resultat->moyen ?></td>
                        <td><?php echo $resultat->evaluation ?></td>
                        <td> <?php
                            if (!empty($resultat->distanciel)) {
                                echo '✔';
                            } ?>
                        </td>
                        <td>
                            <a href="#"
                               onclick="document.getElementById('edit-cr-<?php echo $resultat->idCompteRendu ?>').submit()">Modifier</a>
                            <a href="#"
                               onclick="document.getElementById('del-cr-<?php echo $resultat->idCompteRendu ?>').submit()">Supprimer</a>
                        </td>
                    </tr>
                    <form action="compte-rendu.php" method="get"
                          id="edit-cr-<?php echo $resultat->idCompteRendu ?>">
                        <input type="hidden" value="<?php echo $resultat->idCompteRendu ?>" name="idCR">
                        <input type="hidden" value="<?php echo $resultat->idFormation ?>" name="formation">
                        <input type="hidden" value="<?php echo $resultat->idPromo ?>" name="promotion">
                        <input type="hidden" value="<?php echo $resultat->idModule ?>" name="module">
                    </form>
                    <form action="" method="post"
                          id="del-cr-<?php echo $resultat->idCompteRendu ?>">
                        <input type="hidden" value="<?php echo $resultat->idCompteRendu ?>" name="del-compte-rendu">
                    </form>
                <?php }
                $query->close(); ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>
</div>
</body>
</html>