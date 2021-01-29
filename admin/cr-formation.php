<?php include("session.php") ?>
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
        <h1 class="text-center mb-4">Compte Rendu par Formations</h1>
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
                    <option hidden selected>Sélectionner une promotion</option>
                    <?php //Requete + verification formation sélectionnée
                    $query = $bdd->query('SELECT idPromo, libelle FROM promo WHERE idFormation = ' . $_GET['formation'] . ' GROUP BY verrouillage, dateDebut, dateFin');
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
    <?php } if (isset($_GET['promotion'])) { ?>
    <div class="col-xl-auto mx-auto">
        <div>
            <?php
            echo '<h2 class="text-center mb-3">Évènements de ' . $reference . '</h2>';
             ob_start() ?>
            <table class="table table-striped border border-3 text-center">
                <thead>
                <tr>
                    <th scope="col">Num. Sem.</th>
                    <th scope="col">Module</th>
                    <th scope="col">Formateur</th>
                    <th scope="col">Date</th>
                    <th scope="col">Durée</th>
                    <th scope="col">Contenu</th>
                    <th scope="col">Moyen</th>
                    <th scope="col">Objectif</th>
                    <th scope="col">Evaluation</th>
                    <th scope="col">Distanciel</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $query = $bdd->query('SELECT modules.libelle as mLibelle, compterendu.duree, nom, prenom, date, contenu, moyen, objectif, evaluation, distanciel FROM (compterendu
                        INNER JOIN modules ON modules.idModule = compterendu.idModule)
                        INNER JOIN users on users.idUser = compterendu.idUser
                        WHERE idPromo = "' . $_GET['promotion'] . '" ORDER BY date');
                while ($resultat = $query->fetch_object()) {
                    echo '<tr>';
                    $date = strtotime($resultat->date);
                    echo '<th scope="row">' . date('W', $date) . '</th>';
                    echo '<td>' . $resultat->mLibelle . '</td>';
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
                ?>
                </tbody>
            </table>
            <?php
            $_SESSION['html'] = ob_get_contents();
            ob_end_flush(); ?>
        </div>
    </div>
    </div>
    </form>
    <form action="../export.php" method="post">
        <input hidden value="<?php echo $reference ?>" name="libelle">
        <input class="btn btn-primary" type="submit" value="Exporter">
    </form>
    <?php } ?>

    <script type="text/javascript">
        function imprimer() {
            var prtContent = document.getElementById("imprimer");
            var WinPrint = window.open('', '', 'left=0,top=0,width=1100,height=600,toolbar=0,scrollbars=0,status=0');
            /*WinPrint.document.write('<link href="../css/bootstrap.css" rel="stylesheet">');
            WinPrint.document.write(prtContent.innerHTML);
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();*/
        }
    </script>

    </div>
    </body>
    </html>


<?php

?>