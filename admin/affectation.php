<?php include("session.php");
if (isset($_POST['affecter'])) {
    $query = 'INSERT INTO formateuraffecte (idModule, idUser, heuresPrevues) VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE heuresPrevues=?';
    $stmt = $bdd->prepare($query);
    $stmt->bind_param("iiii", $_GET['module'], $_POST['formateur'], $_POST['nbHeures'], $_POST['nbHeures']);
    if (!$stmt->execute()) {
        printf("Erreur : %s\n", $stmt->error);
        $alertFail = "L'affectation n'a pas pu être créée ou modifiée.";
    } else {
        $alertSuccess = "L'affectation a bien été créée ou modifiée.";
    }
}
$title = "Ajout d'un formateur";
if (isset($_POST['edit-user'])) {
    $query = $bdd->query('SELECT idUser, heuresPrevues FROM formateuraffecte WHERE idUser = ' . $_POST['edit-user'] . ' AND idModule = ' . $_GET['module']);
    $resultEdit = $query->fetch_object();
    $query->close();
    $title = "Modifier : ";
    $button = "Modifier";
}
if (isset($_POST['del-user'])) {
    if (!$bdd->query('DELETE FROM formateuraffecte WHERE IdUser = "' . $_POST['del-user'] . '"')) {
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
            <form class="mb-3" action="" method="get" name="formations">
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
            <form class="mb-3" action="" method="get" name="modules">
                <?php if (isset($_GET['formation'])) {
                    echo '<input type="hidden" name="formation" value="' . $_GET['formation'] . '">';
                    /* utilisé pour affecter la valeur de formation avec le nouveau form, et pouvoir reset la valeur de module
                    au changement de formation */ ?>
                    <div class="form-floating">
                        <select class="form-select" name="module" onchange="this.form.submit()">
                            <option hidden selected>Sélectionner un module</option>
                            <?php //Requete + verification module sélectionné
                            $query = $bdd->query('SELECT * FROM modules WHERE idFormation = ' . $_GET['formation']);
                            while ($resultat = $query->fetch_object()) {
                                ;
                                echo '<option value="' . $resultat->idModule . '"';
                                if (isset($_GET['module'])) {
                                    if ($_GET['module'] == $resultat->idModule) {
                                        echo 'selected';
                                        $reference = $resultat->reference;
                                        $heuresTotal = $resultat->nbHeures;
                                    }
                                }
                                echo '>' . $resultat->libelle . '</option>';
                            }
                            $query->close();
                            ?>
                        </select>
                        <label for="module">Module</label>
                    </div>
                <?php } ?>
            </form>
            <form action="" method="post" name="module">
                <?php if (isset($_GET['module'])) { ?>
                <div class="form-floating mb-3">
                    <select class="form-select" name="formateur">
                        <option selected hidden>Affecter un formateur</option>
                        <?php
                        if (!isset($_POST['edit-user'])) {
                            $sql = 'SELECT idUser, nom, prenom FROM users
                        WHERE admin !=1 AND idUser NOT IN (SELECT idUser FROM formateuraffecte WHERE idModule = ' . $_GET['module'] . ')
                        GROUP BY nom, prenom';
                        } else {
                            $sql = 'SELECT idUser, nom, prenom FROM users
                        WHERE admin !=1 AND idUser NOT IN (SELECT idUser FROM formateuraffecte WHERE idModule = ' . $_GET['module'] . ')
                        OR idUser = ' . $resultEdit->idUser . ' GROUP BY nom, prenom';
                        }
                        $query = $bdd->query($sql);
                        while ($resultat = $query->fetch_object()) {
                            echo '<option value="' . $resultat->idUser . '"';
                            if(isset($_POST['edit-user'])) if ($_POST['edit-user'] == $resultat->idUser) echo 'selected';
                            echo '>'.$resultat->nom . ' ' . $resultat->prenom . '</option>';
                        }
                        $query->close();
                        ?>
                    </select>
                    <label class="form-label" for="formateur">Formateur *</label>
                </div>
                <div class="mb-3">
                    <div class="mb-3 input-group">
                        <input class="form-control" name="nbHeures" type="number"
                               placeholder="Nombre d'heures" required <?php if (isset($_POST['edit-user'])) echo 'value="' . $resultEdit->heuresPrevues . '"' ?>>
                        <span class="input-group-text">heures</span>
                    </div>
                </div>
                <input class="btn btn-primary" type="submit" value="<?php echo $button ?>" name="affecter">
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
            </form>
        </div>
        <div class="col-md-auto mx-auto">
            <h1 class="text-center mb-4">Liste des formateurs du module <?php echo $reference ?></h1>
            <?php if (!empty($alertDelFail)) {
                echo '<div class="mt-3 alert alert-danger text-center">'
                    . $alertDelFail .
                    '</div>';
            } elseif (!empty($alertDelSuccess)) {
                echo '<div class="mt-3 alert alert-success text-center">'
                    . $alertDelSuccess .
                    '</div>';
            } ?>
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
                $query = $bdd->query('SELECT * FROM formateuraffecte
INNER JOIN users ON users.idUser = formateuraffecte.idUser WHERE idModule = ' . $_GET['module']);
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
                    </td> <?php
                    echo '<form action="" method="post" id="edit-user-' . $resultat->idUser . '">';
                    echo '<input hidden value="' . $resultat->idUser . '" name="edit-user">';
                    echo '</form>';
                    echo '<form action="" method="post" id="del-user-' . $resultat->idUser . '">';
                    echo '<input hidden value="' . $resultat->idUser . '" name="del-user">';
                    echo '</form>';
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
                    <td><?php if ($calculHeure == $heuresTotal) {
                        echo "✔" ;
                        if (isset($_POST['affecter']))
                        $bdd->query('UPDATE modules SET nbHeureBon = 1 WHERE idModule =  ' . $_GET['module']);
                    } else?></td>
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