<?php include("session.php");
if (isset($_POST['formation'])) {
    $query = 'INSERT INTO formations (libelle, reference, duree, volumeHoraire) VALUES (?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE libelle=?, duree=?, volumeHoraire=?';
    $stmt = $bdd->prepare($query);
    $stmt->bind_param("ssiisii", $_POST['libelle'], $_POST['reference'], $_POST['duree'], $_POST['volumeHoraire'], $_POST['reference'], $_POST['duree'], $_POST['volumeHoraire']);
    if (!$stmt->execute()) {
        printf("Erreur : %s\n", $stmt->error);
        $alertFail = "La formation n'a pas pu être ajoutée ou modifée.";
    } else {
        $alertSuccess = "La formation a bien été ajoutée ou modifiée.";
    }
}
$title = "Ajout de formation";
if (isset($_POST['edit-reference'])) {
    $query = $bdd->query('SELECT reference, libelle, duree, volumeHoraire FROM formations WHERE reference = "' . $_POST['edit-reference'] . '"');
    $resultEdit = $query->fetch_object();
    $query->close();
    $title = "Modifier : " . $resultEdit->reference;
    $button = "Modifier";
}
if (isset($_POST['del-reference'])) {
    if (!$bdd->query('DELETE FROM formations WHERE reference = "' . $_POST['del-reference'] . '"')) {
        $alertDelFail = "La formation n'a pas pu être supprimée.";
    } else {
        $alertDelSuccess = "La formation a bien été supprimée.";
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
    <title>Formations</title>
</head>
<body>
<?php include("navbar.php"); ?>
<div class="container">
    <div class="row">
        <div class="col-xl-auto mx-auto text-center"> <!--Formulaire de création d'une formation-->
            <h1 class="px-5"><?php echo $title ?></h1>
            <form action="" method="post" name="formation">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-floating">
                            <input class="form-control" name="reference" type="text" placeholder="Référence"
                                   required
                                   maxlength="20" <?php if (isset($_POST['edit-reference'])) echo 'readonly value="' . $resultEdit->reference . '"' ?>>
                            <label for="reference">Référence *</label>
                        </div>
                    </div>
                    <div class="col-md-8 mb-3">
                        <div class="form-floating">
                            <input class="form-control" name="libelle" type="text" placeholder="Nom de la formation"
                                   required
                                   maxlength="100" <?php if (isset($_POST['edit-reference'])) echo 'value="' . $resultEdit->libelle . '"' ?>>
                            <label for="libelle">Nom de la formation *</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3 input-group">
                    <input class="form-control" name="duree" type="number"
                           placeholder="Durée de la formation" <?php if (isset($_POST['edit-reference'])) echo 'value="' . $resultEdit->duree . '"' ?>>
                    <span class="input-group-text">mois</span>
                </div>
                <div class="mb-3 input-group">
                    <input class="form-control" name="volumeHoraire" type="number"
                           placeholder="Volume Horaire" <?php if (isset($_POST['edit-reference'])) echo 'value="' . $resultEdit->volumeHoraire . '"' ?>>
                    <span class="input-group-text">heures</span>
                </div>
                <p>Les champs indiqués par une * sont obligatoires</p>
                <input class="btn btn-primary" type="submit" value="<?php echo $button ?>" name="formation">
            </form>
            <?php if (!empty($alertSuccess)) {
                echo '<div class="mt-3 alert alert-success text-center">'
                    . $alertSuccess .
                    '</div>';
            } elseif (!empty($alertFail)) {
                echo '<div class="mt-3 alert alert-danger text-center">'
                    . $alertFail .
                    '</div>';
            }
            ?>
        </div>
        <div class="col-xl-auto mx-auto mb-3"> <!--Tableau de la liste des Formations-->
            <h1 class="text-center mb-4">Liste des formations</h1>
            <?php if (!empty($alertDelFail)) {
                echo '<div class="mt-3 alert alert-danger text-center">'
                    . $alertDelFail .
                    '</div>';
            } elseif (!empty($alertDelSuccess)) {
                echo '<div class="mt-3 alert alert-success text-center">'
                    . $alertDelSuccess .
                    '</div>';
            } ?>
            <table class="table table-striped table-hover border border-3 text-center">
                <thead>
                <tr>
                    <th scope="col">Référence</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Durée</th>
                    <th scope="col">Volume Horaire</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $query = $bdd->query('SELECT * FROM formations');
                while ($resultat = $query->fetch_object()) {
                    echo "<tr>";
                    echo '<td><a class="link-dark" title="Accéder aux modules de cette formation"
                    href="modules.php?formation=' . $resultat->idFormation . '">' . $resultat->reference . "</a></td>";
                    echo '<td><a class="link-dark" title="Accéder aux modules de cette formation"
                    href="modules.php?formation=' . $resultat->idFormation . '">' . $resultat->libelle . '</a></td>';
                    echo "<td>" . $resultat->duree . " mois</td>";
                    echo "<td>" . $resultat->volumeHoraire . " h</td>"; ?>
                    <td>
                        <a href="#"
                           onclick="document.getElementById('edit-form-<?php echo $resultat->reference; ?>').submit()">Modifier</a>
                        <a href="#"
                           onclick="document.getElementById('del-form-<?php echo $resultat->reference; ?>').submit()">Supprimer</a>
                    </td>
                    </tr>
                    <form action="" method="post" id="edit-form-<?php echo $resultat->reference ?>">
                        <input type="hidden" value="<?php echo $resultat->reference ?>" name="edit-reference">
                    </form>
                    <form action="" method="post" id="del-form-<?php echo $resultat->reference ?>">
                        <input type="hidden" value="<?php echo $resultat->reference ?>" name="del-reference">
                    </form>
                <?php }
                $query->close(); ?>

                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
