<?php include("session.php"); ?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="../css/bootstrap.css" rel="stylesheet">
    <title>Compte Rendu</title>
</head>
<body>
<?php include("navbar.php") ?>
<div class="container">
    <h1 class="text-center mb-4">Compte Rendu par Modules</h1>
    <form class="mb-3" action="" method="get" name="formations">
        <div class="row">
            <div class="col-xl-auto mx-auto mb-3">
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
                                    $reference = $resultat->reference;
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
                <option hidden selected>Sélectionner un module</option>
                <?php //Requete + verification module sélectionné
                $query = $bdd->query('SELECT * FROM modules WHERE idFormation = ' . $_GET['formation']);
                while ($resultat = $query->fetch_object()) {
                    ;
                    echo '<option value="' . $resultat->idModule . '"';
                    if (isset($_GET['module'])) {
                        if ($_GET['module'] == $resultat->idModule) {
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
                <option hidden selected value="">Sélectionner une promotion</option>
                <?php //Requete + verification formation sélectionnée
                $query = $bdd->query('SELECT idPromo, libelle FROM promotions WHERE idFormation = ' . $_GET['formation'] . ' GROUP BY verrouillage, dateDebut, dateFin');
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
</div>
<?php } if (isset($_GET['module'])) { ?>
<div class="col-xl-auto mx-auto">
    <div id="imprimer">
        <?php
        echo '<h2 class="text-center mb-3">Évènements de ' . $reference . '</h2>'
        ?>
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
            <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (!$_GET['promotion']) {
                $sql = 'SELECT modules.libelle AS mLibelle, compterendu.duree, nom, prenom, date, contenu, moyen, objectif, evaluation, distanciel FROM (compterendu
                        INNER JOIN modules ON modules.idModule = compterendu.idModule)
                        INNER JOIN users ON users.idUser = compterendu.idUser
                        WHERE modules.idModule = ' . $_GET['module'] . ' ORDER BY date';
            } else {
                $sql = 'SELECT modules.libelle AS mLibelle, compterendu.duree, nom, prenom, date, contenu, moyen, objectif, evaluation, distanciel FROM (compterendu
                        INNER JOIN modules ON modules.idModule = compterendu.idModule)
                        INNER JOIN users ON users.idUser = compterendu.idUser
                        WHERE modules.idModule = ' . $_GET['module'] . ' AND idPromo = ' . $_GET['promotion'] . ' ORDER BY date';
            }
            $query = $bdd->query($sql);
            while ($resultat = $query->fetch_object()) {
                echo '<tr>';
                $date = strtotime($resultat->date);
                echo '<th scope="row">' . date('W', $date) . '</th>';
                echo '<td>' . $resultat->nom . ' ' . $resultat->prenom . '</td>';
                echo '<td>' . date('d/m/Y', $date) . '</td>';
                echo '<td>' . $resultat->duree . 'h</td>';
                echo '<td>' . $resultat->contenu . '</td>';
                echo '<td>' . $resultat->moyen . '</td>';
                echo '<td>' . $resultat->objectif . '</td>';
                echo '<td>' . $resultat->evaluation . '</td>';
                echo '<td>';
                if (!empty($resultat->distanciel)) {
                    echo '✔';
                }
                echo '</td>';
                echo '</tr>';

            }
            $query->close();
            ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</form>
<form>
    <input class="btn btn-primary" type="button" value="Imprimer le tableau" onClick="imprimer()">
</form>
<?php } ?>
</div>
</body>
</html>