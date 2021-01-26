<?php include("session.php");
if (isset($_POST['module'])) {
    $query = 'INSERT INTO modules (libelle, reference, nbHeures, commentaire, idFormation) VALUES (?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE libelle=?, nbHeures=?, commentaire=?';
    $stmt = $bdd->prepare($query);
    $stmt->bind_param("ssisisis", $_POST['libelle'], $_POST['reference'], $_POST['nbHeures'], $_POST['commentaire'], $_GET['formation'], $_POST['libelle'], $_POST['nbHeures'], $_POST['commentaire']);
    if (!$stmt->execute()) {
        printf("Erreur : %s\n", $stmt->error);
        $alertFail = "Le module n'a pas pu être ajouté ou modifé.";
    } else {
        $alertSuccess = "Le module a bien été ajouté ou modifié.";
    }
}
$title = "Ajout de Modules";
if (isset($_POST['edit-reference'])) {
    $query = $bdd->query('SELECT * FROM modules WHERE reference = "' . $_POST['edit-reference'] . '"');
    $resultEdit = $query->fetch_object();
    $query->close();
    $title = "Modifier : " . $resultEdit->libelle;
    $button = "Modifier";
}
if (isset($_POST['del-reference'])) {
    if (!$bdd->query('DELETE FROM modules WHERE reference = "' . $_POST['del-reference'] . '"')) {
        $alertDelFail = "Le module n'a pas pu être supprimé.";
    } else {
        $alertDelSuccess = "Le module a bien été supprimé.";
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
    <title>Modules</title>
</head>
<body>
<?php include("navbar.php"); ?>
<div class="container text-center">
    <div class="row"> <!--Tableau de la liste des Formateurs-->
        <div class="col-md-auto mx-auto text-center mb-4"> <!--Formulaire de création d'un formateur-->
            <h1 class="mb-4"><?php echo $title ?></h1>
            <form action="" method="get" name="formation">
                <div class="mb-3">
                    <div class="form-floating">
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
                        <label for="formation">Formation du module</label>
                    </div>
                </div>
            </form>
            <?php if (isset($_GET['formation'])) { ?>
            <form action="" method="post" name="module">
                <div class="row mb-3">
                    <div class="col-md-5">
                        <div class="form-floating">
                            <input class="form-control" name="reference" type="text" placeholder="Reference"
                                   required<?php if (isset($_POST['edit-reference'])) echo 'readonly value="' . $resultEdit->reference . '"' ?>>
                            <label for="reference">Reference *</label>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-floating">
                            <input class="form-control" name="libelle" type="text" placeholder="Nom du module"
                                   required <?php if (isset($_POST['edit-reference'])) echo 'value="' . $resultEdit->libelle . '"' ?>>
                            <label for="libelle">Nom du module *</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3 input-group">
                    <input class="form-control" name="nbHeures" type="number"
                           placeholder="Nombre d'heures *" <?php if (isset($_POST['edit-reference'])) echo 'value="' . $resultEdit->nbHeures . '"' ?>>
                    <span class="input-group-text">heures</span>
                </div>
                <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Laisser un commentaire" name="commentaire"
                                  maxlength="255"
                                  style="height: 100px"><?php if (isset($_POST['edit-reference'])) echo $resultEdit->commentaire ?></textarea>
                    <label for="commentaire">Commentaire</label>
                </div>
                <p>Les champs indiqués par une * sont obligatoires</p>
                <input class="btn btn-primary" type="submit" value="<?php echo $button ?>" name="module">
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
        <div class="col-md-auto mx-auto">
            <h1 class="text-center mb-4">Liste des modules de <?php echo $reference ?></h1>
            <?php if (!empty($alertDelFail)) {
                echo '<div class="mt-3 alert alert-danger text-center">'
                    . $alertDelFail .
                    '</div>';
            } elseif (!empty($alertDelSuccess)) {
                echo '<div class="mt-3 alert alert-success text-center">'
                    . $alertDelSuccess .
                    '</div>';
            } ?>
            <table class="table table-striped border border-3">
                <thead>
                <tr>
                    <th scope="col">Reference</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Nombre d'heures</th>
                    <th scope="col">Commentaire</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $query = $bdd->query('SELECT * FROM modules WHERE idFormation = ' . $_GET['formation']);
                while ($resultat = $query->fetch_object()) {
                    echo "<tr>";
                    echo "<td>" . $resultat->reference . "</td>";
                    echo "<td>" . $resultat->libelle . "</td>";
                    echo "<td>" . $resultat->nbHeures . "</td>";
                    echo "<td>" . $resultat->commentaire . "</td>"; ?>
                    <td>
                        <a href="#"
                           onclick="document.getElementById('edit-mod-<?php echo $resultat->reference; ?>').submit()">Modifier</a>
                        <a href="#"
                           onclick="document.getElementById('del-mod-<?php echo $resultat->reference; ?>').submit()">Supprimer</a>
                    </td>
                    </tr>
                    <?php
                    echo '<form action="" method="post" id="edit-mod-' . $resultat->reference . '">';
                    echo '<input hidden value="' . $resultat->reference . '" name="edit-reference">';
                    echo '</form>';
                    echo '<form action="" method="post" id="del-mod-' . $resultat->reference . '">';
                    echo '<input hidden value="' . $resultat->reference . '" name="del-reference">';
                    echo '</form>';
                } ?>
                </tbody>
            </table>
        </div>
        <?php } ?>
    </div>
</body>
</html>