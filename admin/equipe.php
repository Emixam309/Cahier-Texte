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
    <titleCahier de Texte/title>
</head>
<body>
<?php include("navbar.php") ?>
<div class="container">
    <h1 class="text-center mb-4">Équipe</h1>
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
                                    $libelle = $resultat->reference;
                                    echo 'selected';
                                }
                            }
                            echo '>' . $resultat->libelle . '</option>';
                        }
                        $query->close(); ?>
                    </select>
                    <label for="formation">Formation</label>
                </div>
            </form>
            <form class="mb-3" action="" method="get" name="promotions">
                <?php if (isset($_GET['formation'])) {
                echo '<input type="hidden" name="formation" value="' . $_GET['formation'] . '">'; ?>
                <div class="form-floating mb-3">
                    <select class="form-select" name="promotion" onchange="this.form.submit()">
                        <option hidden selected >Séléctionner une promotion</option>
                        <?php //Requete + verification formation sélectionnée
                        $query = $bdd->query('SELECT idPromo, libelle FROM promotions WHERE idFormation = ' . $_GET['formation'] . ' ORDER BY verrouillage, dateDebut, dateFin');
                        while ($resultat = $query->fetch_object()) {
                            ;
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
        <?php } if (isset($_GET['promotion'])) { ?>
        <div class="col-xl-auto mx-auto">
            <?php
            echo '<h2 class="text-center mb-3">Cahier de Texte de ' . $libelle . '</h2>';
            ob_start() ?>
            <table class="table table-bordered table-striped border border-3 text-center">
                <thead>
                <tr>
                    <th scope="col">Module</th>
                    <th scope="col">Formateur</th>
                    <th scope="col">Nbr. Heures</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    $query = $bdd->query('SELECT modules.idFormation, modules.idModule, modules.libelle as mLibelle, nom, prenom, heuresPrevues FROM ((affectation
                    INNER JOIN modules ON modules.idModule = affectation.idModule)
                    INNER JOIN users on users.idUser = affectation.idUser)
                    WHERE idPromo = ' . $_GET['promotion'] . ' ORDER BY modules.libelle, nom');
                while ($resultat = $query->fetch_object()) { ?>
                    <tr>
                        <td><?php echo $resultat->mLibelle ?></td>
                        <td><?php echo $resultat->nom . ' ' . $resultat->prenom ?></td>
                        <td><?php echo $resultat->heuresPrevues ?></td>
                    <?php
                }
                $query->close(); ?>
                </tbody>
            </table>
            <?php
            $_SESSION['html'] = ob_get_contents();
            ob_end_flush(); ?>
            <form action="../export-cr.php" method="post">
                <input type="hidden" value="<?php echo $libelle ?>" name="libelle">
                <input class="btn btn-primary" type="submit" value="Exporter en PDF">
            </form>
        </div>
    </div>
    <?php } ?>
</body>
</html>