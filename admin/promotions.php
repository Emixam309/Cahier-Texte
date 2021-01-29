<?php include("session.php");
if (isset($_POST['promotion'])) {
    $dateDebut = date("Y-m-d", strtotime($_POST['dateDebut']));
    $dateFin = date("Y-m-d", strtotime($_POST['dateFin']));
    if ($dateDebut <= $dateFin) {
        $query = 'INSERT INTO promo (libelle, dateDebut, dateFin, idFormation) VALUES (?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE dateDebut=?, dateFin=?';
        $stmt = $bdd->prepare($query);
        $stmt->bind_param("sssiss", $_POST['libelle'], $_POST['dateDebut'], $_POST['dateFin'], $_GET['formation'], $_POST['dateDebut'], $_POST['dateFin']);
        if (!$stmt->execute()) {
            printf("Erreur : %s\n", $stmt->error);
            $alertFail = "La promotion n'a pas pu être ajoutée ou modifiée.";
        } else {
            $alertSuccess = "La promotion a bien été ajoutée ou modifiée.";
        }
    } else $alertFail = "La date de début est supérieure a la date de fin";
}
$title = "Ajout de Promotions";
if (isset($_POST['edit-libelle'])) {
    $query = $bdd->query('SELECT * FROM promo WHERE libelle = "' . $_POST['edit-libelle'] . '"');
    $resultEdit = $query->fetch_object();
    $query->close();
    $title = "Modifier : " . $resultEdit->libelle;
    $button = "Modifier";
}
if (isset($_POST['del-libelle'])) {
    if (!$bdd->query('DELETE FROM promo WHERE libelle = "' . $_POST['del-libelle'] . '"')) {
        $alertDelFail = "La promotion n'a pas pu être supprimée.";
    } else {
        $alertDelSuccess = "La promotion a bien été supprimée.";
    }
}
if (isset($_POST['ver-libelle'])) {
    if (!$bdd->query('UPDATE promo SET verrouillage = ' . $_POST['verrouillage'] . ' WHERE libelle = "' . $_POST['ver-libelle'] . '"')) {
        $alertVerFail = "La promotion n'a pas pu être clôturée.";
    } else {
        if ($_POST['verrouillage'] == 1) {
            $alertVerSuccess = "La promotion a bien été clôturée.";
        } else {
            $alertVerSuccess = "La promotion a bien été déclôturée.";
        }
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
    <title>Promos</title>
</head>
<body>
<?php include("navbar.php"); ?>
<div class="container">
    <div class="row">
        <div class="col-xl-auto mx-auto text-center mb-3">
            <h1 class="text-center mb-4"><?php echo $title ?></h1>
            <form action="" method="get" name="formation">
                <div class="form-floating mb-3">
                    <select class="form-select" name="formation" onchange="this.form.submit()">
                        <option hidden selected>Sélectionner une formation</option>
                        <?php //Requete + verification formation sélectionnée
                        $query = $bdd->query('SELECT idFormation, libelle, reference FROM formations');
                        while ($resultat = $query->fetch_object()) {
                            ;
                            echo '<option value="' . $resultat->idFormation . '"';
                            if (isset($_GET['formation'])) {
                                if ($_GET['formation'] == $resultat->idFormation) {
                                    echo 'selected';
                                    $reference = $resultat->reference;
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
            <?php if (isset($_GET['formation'])) { ?>
            <form action="" method="post" name="promotion">
                <div class="form-floating mb-3">
                    <input class="form-control" name="libelle" type="text" placeholder="Référence de la promotion"
                        <?php if (isset($_POST['edit-libelle'])) echo 'readonly value="' . $resultEdit->libelle . '"';
                        else echo 'value="' . $reference . ' "'; ?> maxlength="30" required>
                    <label for="libelle">Référence</label>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input class="form-control" name="dateDebut"
                                   type="date"
                                   required <?php if (isset($_POST['edit-libelle'])) echo 'value="' . $resultEdit->dateDebut . '"' ?>>
                            <label for="DateDebut">Date de début</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input class="form-control" name="dateFin"
                                   type="date"
                                   required <?php if (isset($_POST['edit-libelle'])) echo 'value="' . $resultEdit->dateFin . '"' ?>>
                            <label for="dateFin">Date de fin</label>
                        </div>
                    </div>
                </div>
                <p>Tous les champs sont obligatoires</p>
                <input class="btn btn-primary" type="submit" value="<?php echo $button ?>" name="promotion">
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
            <h1 class="text-center mb-4">Liste des promotions de <?php echo $reference ?></h1>
            <?php if (!empty($alertDelFail)) {
                echo '<div class="mt-3 alert alert-danger text-center">'
                    . $alertDelFail .
                    '</div>';
            } elseif (!empty($alertDelSuccess)) {
                echo '<div class="mt-3 alert alert-success text-center">'
                    . $alertDelSuccess .
                    '</div>';
            }
            if (!empty($alertVerFail)) {
                echo '<div class="mt-3 alert alert-danger text-center">'
                    . $alertVerFail .
                    '</div>';
            } elseif (!empty($alertVerSuccess)) {
                echo '<div class="mt-3 alert alert-success text-center">'
                    . $alertVerSuccess .
                    '</div>';
            } ?>
            <table class="table table-striped border border-3 text-center">
                <thead>
                <tr>
                    <th scope="col">Référence</th>
                    <th scope="col">Date de début</th>
                    <th scope="col">Date de fin</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $query = $bdd->query('SELECT libelle, dateDebut, dateFin, verrouillage FROM promo WHERE idFormation = ' . $_GET['formation'] . ' GROUP BY verrouillage, dateDebut, dateFin');
                while ($resultat = $query->fetch_object()) {
                    echo '<form action="" method="post" id="edit-promo-' . $resultat->libelle . '">';
                    echo '<input hidden value="' . $resultat->libelle . '" name="edit-libelle">';
                    echo '</form>';
                    echo '<form action="" method="post" id="del-promo-' . $resultat->libelle . '">';
                    echo '<input hidden value="' . $resultat->libelle . '" name="del-libelle">';
                    echo '</form>';
                    echo '<form action="" method="post" id="ver-promo-' . $resultat->libelle . '">';
                    echo '<input hidden value="' . $resultat->libelle . '" name="ver-libelle">';
                    if ($resultat->verrouillage == 0) {
                        $verouillage = "Clôturer";
                        $valVerrouillage = 1;
                    } else {
                        $verouillage = "Déclôturer";
                        $valVerrouillage = 0;
                    }
                    echo '<input hidden value="' . $valVerrouillage . '" name="verrouillage">';
                    echo '</form>';
                    if ($resultat->verrouillage != 1) {
                        echo "<tr>";
                    } else {
                        echo '<tr class="table-secondary">';
                    }
                    echo "<td>" . $resultat->libelle . "</td>";
                    $date = strtotime($resultat->dateDebut);
                    echo "<td>" . date('d/m/Y', $date) . "</td>";
                    $date = strtotime($resultat->dateFin);
                    echo "<td>" . date('d/m/Y', $date) . "</td>"; ?>
                    <td>
                        <a href="#"
                           onclick="document.getElementById('ver-promo-<?php echo $resultat->libelle; ?>').submit()"><?php echo $verouillage ?></a>
                        <a href="#"
                           onclick="document.getElementById('edit-promo-<?php echo $resultat->libelle; ?>').submit()">Modifier</a>
                        <a href="#"
                           onclick="document.getElementById('del-promo-<?php echo $resultat->libelle; ?>').submit()">Supprimer</a>
                    </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <?php } ?>
    </div>
</body>
</html>
