<?php include("session.php");
if (isset($_POST['del-compte-rendu'])) {
    if (!$bdd->query('DELETE FROM compterendu WHERE idCompteRendu = "' . $_POST['del-compte-rendu'] . '"')) {
        printf("Erreur : %s\n", $bdd->error);
        $alertDelFail = "Le compte rendu n'a pas pu être supprimé.";
    } else {
        $alertDelSuccess = "Le compte rendu a bien été supprimé.";
    }
} ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="../css/bootstrap.css" rel="stylesheet">
    <title>Cahier de Texte</title>
</head>
<body>
<?php include("navbar.php") ?>
<div class="container">
    <h1 class="text-center mb-4">Cahier de Texte par Modules</h1>
    <div class="row">
        <div class="col-xl-auto mx-auto mb-3">
            <form class="mb-3" action="" method="get" name="formations">
                <div class="form-floating mb-3">
                    <select class="form-select" name="formation" onchange="this.form.submit()">
                        <option hidden selected>Sélectionner une formation</option>
                        <?php //Requete + verification formation sélectionnée
                        $query = $bdd->query('SELECT formations.idFormation, formations.libelle, formations.reference FROM formations
                        INNER JOIN modules ON formations.idFormation = modules.idFormation
                        GROUP BY libelle');
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
                    <select class="form-select" name="module" onchange="this.form.submit()">
                        <option hidden selected value="">Sélectionner un module</option>
                        <?php //Requete + verification module sélectionné
                        $query = $bdd->query('SELECT * FROM modules WHERE idFormation = ' . $_GET['formation']);
                        while ($resultat = $query->fetch_object()) {
                            ;
                            echo '<option value="' . $resultat->idModule . '"';
                            if (isset($_GET['module'])) {
                                if ($_GET['module'] == $resultat->idModule) {
                                    $reference = $resultat->reference;
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
                <div class="form-floating mb-3">
                    <select class="form-select" name="promotion" onchange="this.form.submit()">
                        <option selected value="">Promotions non clôturées</option>
                        <?php //Requete + verification formation sélectionnée
                        $query = $bdd->query('SELECT idPromo, libelle FROM promotions WHERE idFormation = ' . $_GET['formation'] . ' GROUP BY verrouillage, dateDebut, dateFin');
                        while ($resultat = $query->fetch_object()) {
                            echo '<option value="' . $resultat->idPromo . '"';
                            if (isset($_GET['promotion'])) {
                                if ($_GET['promotion'] == $resultat->idPromo) {
                                    $libelle = $resultat->libelle;
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
        </div>
        <?php } if (!empty($_GET['module'])) { ?>
        <div class="col-xl-auto mx-auto">
            <?php
            echo '<h2 class="text-center mb-3">Cahier de Texte de ' . $reference . '</h2>';
            ob_start() ?>
            <table class="table table-striped border border-3 text-center">
                <thead>
                <tr>
                    <th scope="col">Num. Sem.</th>
                    <th scope="col">Formateur</th>
                    <th scope="col">Date</th>
                    <th scope="col">Durée</th>
                    <th scope="col">Contenu</th>
                    <th scope="col">Moyen</th>
                    <th scope="col">Objectif</th>
                    <th scope="col">Evaluation</th>
                    <th scope="col">Distanciel</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (empty($_GET['promotion'])) {
                    $sql = 'SELECT idCompteRendu, promotions.idFormation, promotions.idPromo, modules.idModule, modules.libelle AS mLibelle, compterendu.duree, nom, prenom, date, contenu, moyen, objectif, evaluation, distanciel FROM ((compterendu
                        INNER JOIN modules ON modules.idModule = compterendu.idModule)
                        INNER JOIN users ON users.idUser = compterendu.idUser)
                        INNER JOIN promotions on compterendu.idPromo = promotions.idPromo
                        WHERE modules.idModule = ' . $_GET['module'] . ' AND verrouillage != 1 ORDER BY date, dateEntree';
                } else {
                    $sql = 'SELECT idCompteRendu, idFormation, idPromo, modules.idModule, modules.libelle AS mLibelle, compterendu.duree, nom, prenom, date, contenu, moyen, objectif, evaluation, distanciel FROM (compterendu
                        INNER JOIN modules ON modules.idModule = compterendu.idModule)
                        INNER JOIN users ON users.idUser = compterendu.idUser
                        WHERE modules.idModule = ' . $_GET['module'] . ' AND idPromo = ' . $_GET['promotion'] . ' ORDER BY date, dateEntree';
                }
                $query = $bdd->query($sql);
                echo $bdd->error;
                while ($resultat = $query->fetch_object()) {
                    $date = strtotime($resultat->date); ?>
                    <tr>
                        <th scope="row"><?php echo date('W', $date) ?></th>
                        <td><?php echo $resultat->nom . ' ' . $resultat->prenom ?></td>
                        <td><?php echo date('d/m/Y', $date) ?></td>
                        <td><?php echo $resultat->duree ?>h</td>
                        <td><?php echo $resultat->contenu ?></td>
                        <td><?php echo $resultat->moyen ?></td>
                        <td><?php echo $resultat->objectif ?></td>
                        <td><?php echo $resultat->evaluation ?></td>
                        <td> <?php
                            if (!empty($resultat->distanciel)) {
                                echo '✔';
                            } ?>
                        </td>
                        <td id="a">
                            <a href="#"
                               onclick="document.getElementById('edit-cr-<?php echo $resultat->idCompteRendu ?>').submit()">Modifier</a>
                            <a href="#"
                               onclick="document.getElementById('del-cr-<?php echo $resultat->idCompteRendu ?>').submit()">Supprimer</a>
                        </td>
                    </tr>
                    <form action="compte-rendu.php" method="post"
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
                $query->close(); ?>
                </tbody>
            </table>
            <?php
            $_SESSION['html'] = ob_get_contents();
            ob_end_flush(); ?>
            <form action="export-cr.php" method="post">
                <?php if (empty($_GET['promotion'])) { ?>
                    <input type="hidden" value="<?php echo 'de ' . $reference ?>" name="libelle">
                <?php } else { ?>
                    <input type="hidden" value="<?php echo 'de ' . $reference . ' pour ' . $libelle ?>" name="libelle">
                <?php } ?>
                <input class="btn btn-primary" type="submit" value="Exporter en PDF">
            </form>
        </div>
    </div>
    <?php } ?>
</div>
</body>
</html>