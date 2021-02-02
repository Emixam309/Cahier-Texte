<?php include("session.php"); ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="../css/bootstrap.css" rel="stylesheet">
    <title>Compte Rendu</title>
</head>
<body>
<?php include("navbar.php") ?>
<div class="container">
    <h1 class="text-center mb-4">Compte Rendu par Formateurs</h1>
    <div class="row">
        <div class="col-xl-auto mx-auto mb-3">
            <form class="mb-3" action="" method="get" name="formations">
                <div class="form-floating mb-3">
                    <select class="form-select" name="formateur" onchange="this.form.submit()">
                        <option selected hidden>Affecter un formateur</option>
                        <?php
                        $sql = 'SELECT idUser, nom, prenom FROM users WHERE admin !=1 AND idUser
                        GROUP BY nom, prenom';
                        $query = $bdd->query($sql);
                        while ($resultat = $query->fetch_object()) {
                            echo '<option value="' . $resultat->idUser . '"';
                            if (isset($_GET['formateur'])) {
                                if ($_GET['formateur'] == $resultat->idUser) {
                                    $nom = $resultat->nom . " " . $resultat->prenom;
                                    echo 'selected';
                                }
                            }
                            echo '>' . $resultat->nom . ' ' . $resultat->prenom . '</option>';
                        }
                        $query->close();
                        ?>
                    </select>
                    <label class="form-label" for="formateur">Formateur</label>
                </div>
            </form>
            <form class="mb-3" action="" method="get" name="promotions">
                <?php if (isset($_GET['formateur'])) {
                echo '<input type="hidden" name="formateur" value="' . $_GET['formateur'] . '">'; ?>
                <div class="form-floating mb-3">
                    <select class="form-select" name="promotion" onchange="this.form.submit()">
                        <option hidden selected>Sélectionner une promotion</option>
                        <?php //Requete + verification formation sélectionnée
                        $query = $bdd->query('SELECT promotions.idPromo, libelle FROM promotions INNER JOIN affectation on promotions.idPromo = affectation.idPromo
                                                    WHERE idUser = ' . $_GET['formateur'] . ' ORDER BY verrouillage, dateDebut, dateFin');
                        while ($resultat = $query->fetch_object()) {
                            ;
                            echo '<option value="' . $resultat->idPromo . '"';
                            if (isset($_GET['promotion'])) {
                                if ($_GET['promotion'] == $resultat->idPromo) {
                                    $reference = $resultat->libelle;
                                    echo 'selected';
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
            <?php } ?>
        </div>
        <?php if (isset($_GET['formateur'])) { ?>
        <div class="col-xl-auto mx-auto">
            <?php
            echo '<h2 class="text-center mb-3">Comptes Rendus de ' . $nom . '</h2>'
            ?>
            <table class="table table-striped border border-3 text-center">
                <thead>
                <tr>
                    <th scope="col">Num. Sem.</th>
                    <th scope="col">Formation</th>
                    <th scope="col">Module</th>
                    <th scope="col">Date</th>
                    <th scope="col">Durée</th>
                    <th scope="col">Contenu</th>
                    <th scope="col">Moyen</th>
                    <th scope="col">Objectif</th>
                    <th scope="col">Evaluation</th>
                    <th scope="col">Distanciel</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!isset($_GET['promotion'])) {
                    $query = $bdd->query('SELECT idCompteRendu, formations.idFormation, promotions.idPromo, modules.idModule, formations.libelle as fLibelle, modules.libelle as mLibelle, compterendu.duree, contenu, moyen, objectif, evaluation, distanciel, date FROM ((compterendu
                        INNER JOIN modules ON modules.idModule = compterendu.idModule)
                        INNER JOIN formations ON formations.idFormation = modules.idFormation)
                        INNER JOIN promotions on compterendu.idPromo = promotions.idPromo
                        WHERE compterendu.idUser = ' . $_GET['formateur'] . ' ORDER BY date, dateEntree');
                } else {
                    $query = $bdd->query('SELECT idCompteRendu, formations.idFormation, promotions.idPromo, modules.idModule, formations.libelle as fLibelle, modules.libelle as mLibelle, compterendu.duree, contenu, moyen, objectif, evaluation, distanciel, date FROM ((compterendu
                        INNER JOIN modules ON modules.idModule = compterendu.idModule)
                        INNER JOIN formations ON formations.idFormation = modules.idFormation)
                        INNER JOIN promotions on compterendu.idPromo = promotions.idPromo
                        WHERE compterendu.idUser = ' . $_GET['formateur'] . ' AND promotions.idPromo = ' . $_GET['promotion'] . ' ORDER BY date, dateEntree');
                }
                while ($resultat = $query->fetch_object()) {
                    $date = strtotime($resultat->date); ?>
                    <tr>
                        <th scope="row"><?php echo date('W', $date) ?></th>
                        <td><?php echo $resultat->fLibelle ?></td>
                        <td><?php echo $resultat->mLibelle ?></td>
                        <td><?php echo date('d/m/Y', $date) ?></td>
                        <td><?php echo $resultat->duree ?>h</td>
                        <td><?php echo $resultat->contenu ?></td>
                        <td><?php echo $resultat->moyen ?></td>
                        <td><?php echo $resultat->objectif ?></td>
                        <td><?php echo $resultat->evaluation ?></td>
                        <td>
                            <?php
                            if (!empty($resultat->distanciel)) {
                                echo '✔';
                            }
                            echo '</td>'; ?>
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
                        <input type="hidden" value="<?php echo $resultat->idCompteRendu ?>"
                               name="del-compte-rendu">
                    </form>
                <?php }
                $query->close();
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <form>
        <input class="btn btn-primary" type="button" value="Imprimer le tableau" onClick="imprimer()">
    </form>
    <?php } ?>
</div>
</body>
</html>