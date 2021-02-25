<?php include("session.php");
if (isset($_POST['affecter'])) { //création de l'affectation
    $query = 'INSERT INTO affectation (idModule, idUser, idPromo, heuresPrevues) VALUES (?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE heuresPrevues=?';
    $stmt = $bdd->prepare($query);
    $stmt->bind_param("iiiii", $_GET['module'], $_POST['user'], $_GET['promotion'], $_POST['nbHeures'], $_POST['nbHeures']);
    if (!$stmt->execute()) {
        printf("Erreur : %s\n", $stmt->error);
        $alertFail = "L'affectation n'a pas pu être créée ou modifiée.";
    } else {
        $alertSuccess = "L'affectation a bien été créée ou modifiée.";
    }
}
$title = "Affecter un formateur";
if (isset($_POST['edit-user'])) { // modfication de l'affecation
    $query = $bdd->query('SELECT idUser, heuresPrevues FROM affectation WHERE idUser = ' . $_POST['edit-user'] . ' AND idModule = ' . $_GET['module']);
    $resultEdit = $query->fetch_object();
    $query->close();
    $title = "Modifier : ";
    $button = "Modifier";
}
if (isset($_POST['del-user'])) { //suppression de l'affectation
    if (!$bdd->query('DELETE FROM affectation WHERE IdUser = "' . $_POST['del-user'] . '"')) {
        $alertDelFail = "L'affectation n'a pas pu être supprimée.";
    } else {
        $alertDelSuccess = "L'affectation a bien été supprimée.";
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
    <title>Affectation</title>
</head>
<body>

<?php include("navbar.php"); ?>
<div class="container">
    <div class="row">
        <div class="col-md-auto mx-auto text-center mb-4">
            <h1 class="mb-4"><?php echo $title ?></h1>
            <form action="" method="get" name="formations"> <!-- Formulaire de sélection de formation -->
                <div class="form-floating mb-3">
                    <select class="form-select" name="formation" onchange="this.form.submit()">
                        <option hidden selected>Sélectionner une formation</option>
                        <?php //Requete + verification formation sélectionnée
                        $query = $bdd->query('SELECT idFormation, libelle FROM formations');
                        while ($resultat = $query->fetch_object()) {
                            ;
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
            <?php if (isset($_GET['formation'])) { //affiche le formulaire si la formation a été sélectionnée ?>
                <form action="" method="get" name="promotion">
                    <?php echo '<input type="hidden" name="formation" value="' . $_GET['formation'] . '">';
                    if (isset($_GET['module']))
                        echo '<input type="hidden" name="module" value="' . $_GET['module'] . '">';
                    /* utilisé pour affecter la valeur de formation avec le nouveau form, et pouvoir reset la valeur de module
                    au changement de formation */ ?>
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
            <?php }
            if (isset($_GET['promotion'])) { //affiche le formulaire si la formation a été sélectionnée ?>
                <form action="" method="get" name="modules"> <!-- Formulaire de sélection de modules -->
                    <?php echo '<input type="hidden" name="formation" value="' . $_GET['formation'] . '">';
                    echo '<input type="hidden" name="promotion" value="' . $_GET['promotion'] . '">';
                    /* utilisé pour affecter la valeur de formation avec le nouveau form, et pouvoir reset la valeur de module
                    au changement de formation */ ?>
                    <div class="form-floating mb-3">
                        <select class="form-select" name="module" onchange="this.form.submit()">
                            <option hidden selected>Sélectionner un module</option>
                            <?php //Requete + verification module sélectionné
                            $query = $bdd->query('SELECT * FROM modules
                            WHERE idFormation = ' . $_GET['formation']);
                            while ($resultat = $query->fetch_object()) {
                                $queryHeures = $bdd->query('SELECT idModule FROM modules
                            WHERE idFormation = ' . $_GET['formation'] . ' AND nbHeures = (SELECT SUM(heuresPrevues) FROM affectation
                            WHERE idPromo = ' . $_GET['promotion'] . ' AND idModule = ' . $resultat->idModule . ')');
                                $resultHeures = $queryHeures->fetch_object();
                                echo '<option value="' . $resultat->idModule . '"';
                                if (isset($_GET['module'])) {
                                    if ($_GET['module'] == $resultat->idModule) {
                                        echo 'selected';
                                        $reference = $resultat->reference;
                                        $heuresTotal = $resultat->nbHeures;
                                    }
                                }
                                if (!empty($resultHeures->idModule)) {
                                    echo '>' . '✔' . $resultat->libelle . '</option>';
                                } else
                                    echo '>' . $resultat->libelle . '</option>';
                            }
                            $queryHeures->close();
                            $query->close();
                            ?>
                        </select>
                        <label for="module">Module</label>
                    </div>
                </form>
            <?php }
            if (isset($_GET['promotion']) and isset($_GET['module'])) { //affiche le formulaire si le module et la promotion ont été sélectionnés ?>
            <form action="" method="post" name="formateur">
                <div class="form-floating mb-3">
                    <select class="form-select" name="user">
                        <option selected hidden>Affecter un formateur</option>
                        <?php
                        if (!isset($_POST['edit-user'])) {
                            $sql = 'SELECT idUser, nom, prenom FROM users
                        WHERE admin != 1 AND actif = 1 AND idUser NOT IN (SELECT idUser FROM affectation WHERE idModule = ' . $_GET['module'] . ' AND idPromo = ' . $_GET['promotion'] . ')
                        GROUP BY nom, prenom';
                        } else {
                            $sql = 'SELECT idUser, nom, prenom FROM users
                        WHERE admin != 1 AND actif = 1  AND idUser NOT IN (SELECT idUser FROM affectation WHERE idModule = ' . $_GET['module'] . ' AND idPromo = ' . $_GET['promotion'] . ')
                        OR idUser = ' . $resultEdit->idUser . ' GROUP BY nom, prenom';
                        }
                        $query = $bdd->query($sql);
                        while ($resultat = $query->fetch_object()) {
                            echo '<option value="' . $resultat->idUser . '"';
                            if (isset($_POST['edit-user'])) if ($_POST['edit-user'] == $resultat->idUser) echo 'selected';
                            echo '>' . $resultat->nom . ' ' . $resultat->prenom . '</option>';
                        }
                        $query->close();
                        ?>
                    </select>
                    <label class="form-label" for="user">Formateur *</label>
                </div>
                <div class="mb-3">
                    <div class="mb-3 input-group">
                        <input class="form-control" name="nbHeures" type="number"
                               placeholder="Nombre d'heures *"
                               required <?php if (isset($_POST['edit-user'])) echo 'value="' . $resultEdit->heuresPrevues . '"' ?>>
                        <span class="input-group-text">heures</span>
                    </div>
                </div>
                <p>Tous les champs sont obligatoires</p>
                <input class="btn btn-primary" type="submit" value="<?php echo $button ?>" name="affecter">
                <?php if (!empty($alertSuccess)) { //affiche l'alerte d'ajout ou de modification
                    echo '<div class="mt-3 alert alert-success text-center">'
                        . $alertSuccess .
                        '</div>';
                } elseif (!empty($alertFail)) {
                    echo '<div class="mt-3 alert alert-danger text-center">'
                        . $alertFail .
                        '</div>';
                }
                ?>
            </form>
        </div>
        <div class="col-md-auto mx-auto">
            <h1 class="text-center mb-4">Liste des formateurs du module <?php echo $reference ?></h1>
            <?php if (!empty($alertDelFail)) { //affiche l'alerte de suppression
                echo '<div class="mt-3 alert alert-danger text-center">'
                    . $alertDelFail .
                    '</div>';
            } elseif (!empty($alertDelSuccess)) {
                echo '<div class="mt-3 alert alert-success text-center">'
                    . $alertDelSuccess .
                    '</div>';
            } ?>
            <!-- Tableau des formateurs du module sélectionné -->
            <table class="table table-striped border border-3 text-center">
                <thead>
                <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Prenom</th>
                    <th scope="col">Nombre d'heures</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $query = $bdd->query('SELECT * FROM affectation
INNER JOIN users ON users.idUser = affectation.idUser WHERE idModule = ' . $_GET['module'] . ' AND idPromo = ' . $_GET['promotion']);
                $calculHeure = 0;
                while ($resultat = $query->fetch_object()) {
                    echo "<tr>";
                    echo "<td>" . $resultat->nom . "</td>";
                    echo "<td>" . $resultat->prenom . "</td>";
                    echo "<td>" . $resultat->heuresPrevues . "</td>"; ?>
                    <td>
                        <a href="#"
                           onclick="document.getElementById('edit-user-<?php echo $resultat->idUser; ?>').submit()">Modifier</a>
                        <a href="#"
                           onclick="document.getElementById('del-user-<?php echo $resultat->idUser; ?>').submit()">Supprimer</a>
                    </td>
                    <!-- Form de modification -->
                    <form action="" method="post" id="edit-user-<?php echo $resultat->idUser ?>">
                        <input type="hidden" value="<?php echo $resultat->idUser ?>" name="edit-user">
                    </form>
                    <!-- Form de suppression -->
                    <form action="" method="post" id="del-user-<?php echo $resultat->idUser ?>">
                        <input type="hidden" value="<?php echo $resultat->idUser ?>" name="del-user">
                    </form>
                    <?php
                    $calculHeure = $calculHeure + $resultat->heuresPrevues;
                }
                ?>
                </tbody>
                <tfoot>
                <?php if ($calculHeure < $heuresTotal) { ?>
                    <tr>
                        <th colspan="2">Nombre d'heures total restantes a affecter</th>
                        <td><?php echo $heuresTotal - $calculHeure ?></td>
                        <td></td>
                    </tr>
                <?php } elseif ($calculHeure > $heuresTotal) { ?>
                    <tr>
                        <th colspan="2">Nombre d'heures total affecté</th>
                        <td><?php echo $calculHeure ?></td>
                        <td></td>
                    </tr>
                <?php } ?>
                <tr>
                    <th colspan="2">Nombre d'heures total du module</th>
                    <td><?php echo $heuresTotal ?></td>
                    <td><?php if ($calculHeure == $heuresTotal) { //indique si le nombre d'heure affecté est bon ou non
                            echo "✔";
                            if (isset($_POST['affecter']))
                                $bdd->query('UPDATE modules SET nbHeuresBon = 1 WHERE idModule =  ' . $_GET['module']);
                        } elseif (isset($_POST['affecter']) or isset($_POST['edit-user']) or isset($_POST['del-user'])) {
                            $bdd->query('UPDATE modules SET nbHeuresBon = 0 WHERE idModule =  ' . $_GET['module']);
                        } ?></td>
                </tr>
                </tfoot>
            </table>
            <?php if ($calculHeure > $heuresTotal) {
                echo '<div class="mt-3 alert alert-danger text-center">
                    Le nombre d\'heures affecté est supérieur au nombre d\'heures de la formation.
                    </div>';
            } ?>
        </div>
    </div>
    <?php } ?>
</div>

</body>
</html>