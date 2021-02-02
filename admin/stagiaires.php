<?php include("session.php");
if (isset($_POST['stagiaire'])) {
    $query = 'INSERT INTO stagiaires (nom, prenom, idPromo) VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE nom=?, prenom=?';
    $stmt = $bdd->prepare($query);
    $stmt->bind_param("ssiss", $_POST['nom'], $_POST['prenom'], $_GET['promotion'], $_POST['nom'], $_POST['prenom']);
    if (!$stmt->execute()) {
        printf("Erreur : %s\n", $stmt->error);
        $alertFail = "Le formateur n'a pas pu être ajouté ou modifié.";
    } else {
        $alertSuccess = "Le formateur a bien été ajouté ou modifié.";
    }
}
$title = "Ajout d'un stagiaire";
if (isset($_POST['edit-stagiaire'])) {
    $query = $bdd->query('SELECT nom, prenom FROM stagiaires WHERE idStagiaire = ' . $_POST['edit-stagiaire']);
    $resultEdit = $query->fetch_object();
    $query->close();
    $title = "Modifier : " . $resultEdit->nom . " " . $resultEdit->prenom;
    $button = "Modifier";
}
if (isset($_POST['del-stagiaire'])) {
    if (!$bdd->query('DELETE FROM stagiaires WHERE idStagiaire = ' . $_POST['del-stagiaire'])) {
        $alertDelFail = "Le stagiaire n'a pas pu être supprimé.";
    } else {
        $alertDelSuccess = "Le stagiaire a bien été supprimé.";
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
    <title>Stagiaires</title>
</head>
<body>
<?php include("navbar.php"); ?>
<div class="container">
    <div class="row">
        <div class="col-xl-auto mx-auto text-center"> <!--Formulaire de création d'un stagiaire-->
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
                        <label for="formation">Formation</label>
                    </div>
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
                            ;
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
            <?php } if (isset($_GET['promotion'])) { ?>
            <form class="mb-3" action="" method="post" name="stagiaire">
                <div class="row">
                    <div class="col-sm mb-3">
                        <div class="form-floating">
                            <input class="form-control" name="nom" type="text"
                                   placeholder="Nom" maxlength="30"
                                   required <?php if (isset($_POST['edit-stagiaire'])) echo 'value="' . $resultEdit->nom . '"' ?>>
                            <label for="nom">Nom</label>
                        </div>
                    </div>
                    <div class="col-sm mb-3">
                        <div class="form-floating">
                            <input class="form-control" name="prenom" type="text"
                                   placeholder="Prenom" maxlength="30"
                                   required <?php if (isset($_POST['edit-stagiaire'])) echo 'value="' . $resultEdit->prenom . '"' ?>>
                            <label for="prenom">Prenom</label>
                        </div>
                    </div>
                </div>
                <p>Tous les champs sont obligatoires</p>
                <input class="btn btn-primary" type="submit" value="<?php echo $button ?>" name="stagiaire">
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
        <div class="col-xl-auto mx-auto">
            <h1 class="text-center mb-4">Liste des stagiaires</h1>
            <?php if (!empty($alertDelFail)) {
                echo '<div class="mt-3 alert alert-danger text-center">'
                    . $alertDelFail .
                    '</div>';
            } elseif (!empty($alertDelSuccess)) {
                echo '<div class="mt-3 alert alert-success text-center">'
                    . $alertDelSuccess .
                    '</div>';
            } ?>
            <!--Tableau de la liste des Stagiaires-->
            <table class="table table-striped border border-3 text-center">
                <thead>
                <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Prenom</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $query = $bdd->query('SELECT * FROM stagiaires WHERE idPromo = ' . $idPromo . ' GROUP BY nom, prenom, idStagiaire');
                while ($resultat = $query->fetch_object()) {
                    echo "<tr>";
                    echo "<td>" . $resultat->nom . "</td>";
                    echo "<td>" . $resultat->prenom . "</td>"; ?>
                    <td>
                        <a href="#"
                           onclick="document.getElementById('del-stagiaire-<?php echo $resultat->idStagiaire; ?>').submit()">Supprimer</a>
                    </td>
                    <form action="" method="post" id="del-mod-<?php echo $resultat->idStagiaire ?>">
                        <input type="hidden" value="<?php echo $resultat->idStagiaire ?>" name="del-stagiaire">
                    </form>
                <?php }
                $query->close(); ?>
                </tbody>
            </table>
            <?php } ?>
        </div>
    </div>
</div>
</body>
</html>