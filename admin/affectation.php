<?php include("session.php");
if (isset($_POST['affecter'])) {
    $query = 'INSERT INTO formateuraffecte (idModule, idUser, heuresPrevues) VALUES (?, ?, ?)';
    $stmt = $bdd->prepare($query);
    $stmt->bind_param("iii", $_GET['module'], $_POST['formateur'], $_POST['nbHeures']);
    if ($stmt->execute()) {
        $message = "Le formateur à été correctement affecté.";
    } else {
        printf("Erreur : %s\n", $stmt->error);
        $erreur = "Le formateur n'a pas pu être affecté.";
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

<?php include("header.php"); ?>
<div class="container">
    <div class="row">
        <div class="col-md-auto mx-auto text-center">
            <h1 class="mb-4">Affectation</h1>
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
                        $query = $bdd->query('SELECT idUser, nom, prenom FROM users
                        WHERE admin !=1 AND idUser NOT IN (SELECT idUser FROM formateuraffecte WHERE idModule = ' . $_GET['module'] . ') GROUP BY nom, prenom');
                        while ($resultat = $query->fetch_object()) {
                            echo '<option value="' . $resultat->idUser . '">' . $resultat->nom . ' ' . $resultat->prenom . '</option>';
                        }
                        $query->close();
                        ?>
                    </select>
                    <label class="form-label" for="formateur">Formateur</label>
                </div>
                <div class="mb-3">
                    <div class="mb-3 input-group">
                        <input class="form-control" name="nbHeures" type="number" placeholder="Nombre d'heures">
                        <span class="input-group-text">heures</span>
                    </div>
                </div>
                <input class="btn btn-primary" type="submit" value="Affecter" name="affecter">
                <?php if (!empty($message)) {
                    echo '<div class="mt-3 alert alert-success text-center">'
                        . $message .
                        '</div>';
                } elseif (!empty($erreur)) {
                    echo '<div class="mt-3 alert alert-danger text-center">'
                        . $erreur .
                        '</div>';
                }
                ?>
            </form>
        </div>
        <div class="col-md-auto mx-auto">
            <h1 class="text-center mb-4">Liste des formateurs du module <?php echo $reference ?></h1>
            <table class="table table-striped border border-3">
                <thead>
                <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Prenom</th>
                    <th scope="col">Nombre d'heures</th>
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
                    echo "<td>" . $resultat->heuresPrevues . "</td>";
                    echo "</tr>";
                    $calculHeure = $calculHeure + $resultat->heuresPrevues;
                }
                ?>
                </tbody>
                <tfoot>
                <?php if ($calculHeure < $heuresTotal) { ?>
                 <tr>
                    <th colspan="2">Nombre d'heures total restantes a affecter</th>
                    <td><?php echo $heuresTotal - $calculHeure ?></td>
                </tr>
                <?php } elseif ($calculHeure > $heuresTotal) { ?>
                <tr>
                    <th colspan="2">Nombre d'heures total affecté</th>
                    <td><?php echo $calculHeure ?></td>
                </tr>
                <?php } ?>
                <tr>
                <th colspan="2">Nombre d'heures total du module</th>
                <td><?php echo $heuresTotal ?></td>
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