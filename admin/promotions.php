<?php include("session.php");

if (isset($_POST['promotion'])) {
    $query = 'INSERT INTO promo (libelle, dateDebut, dateFin, idFormation) VALUES (?, ?, ?, ?)';
    $stmt = $bdd->prepare($query);
    $stmt->bind_param("sssi", $_POST['libelle'], $_POST['dateDebut'], $_POST['dateFin'], $_GET['formation']);
    if (!$stmt->execute()) {
        printf("Erreur : %s\n", $stmt->error);
        $message = "La promo n'a pas pu être ajoutée.";
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
<?php include("header.php"); ?>
<div class="container">
    <div class="row">
        <div class="col-xl-auto mx-auto text-center mb-3">
            <h1 class="text-center mb-4">Création de Promotions</h1>
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
                    <input class="form-control" name="libelle" type="text" placeholder="Nom de la promotion" value="<?php echo $reference ?> ">
                    <label for="libelle">Nom de la promotion</label>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input class="form-control" name="dateDebut" type="date">
                            <label for="DateDebut">Date de début</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input class="form-control" name="dateFin" type="date">
                            <label for="dateFin">Date de fin</label>
                        </div>
                    </div>
                </div>
                <input class="btn btn-primary" type="submit" value="Ajouter" name="promotion">
            </form>
        </div>


        <div class="col-xl-auto mx-auto mb-3"> <!--Tableau de la liste des Formations-->
            <h1 class="text-center mb-4">Liste des promotions de <?php echo $reference ?></h1>
            <table class="table table-striped border border-3">
                <thead>
                <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Date de début</th>
                    <th scope="col">Date de fin</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $query = $bdd->query('SELECT * FROM promo');
                while ($resultat = $query->fetch_object()) {
                    echo "<tr>";
                    echo "<td>" . $resultat->libelle . "</td>";
                    $date = strtotime($resultat->dateDebut);
                    echo "<td>" . date('d/m/Y', $date) . "</td>";
                    $date = strtotime($resultat->dateFin);
                    echo "<td>" . date('d/m/Y', $date) . "</td>";
                    echo "</tr>";
                } ?>

                </tbody>
            </table>
        </div>
        <?php } ?>
    </div>
</body>
</html>
