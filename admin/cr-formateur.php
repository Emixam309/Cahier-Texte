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
        <h1 class="text-center mb-4">Compte Rendu par Formateurs</h1>
        <form class="mb-3" action="" method="get" name="formations">
            <div class="row">
                <div class="col-xl-auto mx-auto mb-3">
                    <div class="form-floating mb-3">
                        <select class="form-select" name="formateur" onchange="this.form.submit()">
                            <option selected hidden>Affecter un formateur</option>
                            <?php
                            $sql = 'SELECT idUser, nom, prenom FROM users WHERE admin !=1 AND idUser
                        GROUP BY nom, prenom';
                            $query = $bdd->query($sql);
                            while ($resultat = $query->fetch_object()) {
                               echo '<option value="' . $resultat->idUser . '"';
                                if (isset($_GET['formateur'])) {
                                    if ($_GET['formateur'] == $resultat->idUser) {
                                        $nom = $resultat->nom ." ". $resultat->prenom;
                                        echo 'selected';
                                    }
                                }
                                echo '>' . $resultat->nom . ' ' . $resultat->prenom . '</option>';
                            }
                            $query->close();
                            ?>
                        </select>
                        <label class="form-label" for="formateur">Formateur</label>
                    </div>
                </div>
                <?php if (isset($_GET['formateur'])) { ?>
                <div class="col-xl-auto mx-auto">
                    <div id="imprimer">
                        <?php
                        echo '<h2 class="text-center mb-3">Évènements de ' . $nom . '</h2>'
                        ?>
                        <table class="table table-striped border border-3 text-center">
                            <thead>
                            <th scope="col">Num. Sem.</th>
                            <th scope="col">Formation</th>
                            <th scope="col">Module</th>
                            <th scope="col">Date</th>
                            <th scope="col">Durée</th>
                            <th scope="col">Contenu</th>
                            <th scope="col">Moyen</th>
                            <th scope="col">Objectif</th>
                            <th scope="col">Evaluation</th>
                            <th scope="col">Distanciel</th>
                            </thead>
                            <tbody>
                            <?php
                            $query = $bdd->query('SELECT formations.libelle as fLibelle, modules.libelle as mLibelle, compterendu.duree, contenu, moyen, objectif, evaluation, distanciel, date FROM (compterendu
                        INNER JOIN modules ON modules.idModule = compterendu.idModule)
                        INNER JOIN formations ON formations.idFormation = modules.idFormation
                        WHERE idUser = "' . $_GET['formateur'] . '" ORDER BY date');
                            while ($resultat = $query->fetch_object()) {
                                echo '<tr>';
                                $date = strtotime($resultat->date);
                                echo '<th scope="row">' . date('W', $date) . '</th>';
                                echo '<td>' . $resultat->fLibelle . '</td>';
                                echo '<td>' . $resultat->mLibelle . '</td>';
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
                    </div>
                </div>
            </div>
        </form>
        <form>
            <input class="btn btn-primary" type="button" value="Imprimer le tableau" onClick="imprimer()">
        </form>
        <?php } ?>
        <script type="text/javascript">
            function imprimer() {
                var prtContent = document.getElementById("imprimer");
                var WinPrint = window.open('', '', 'left=0,top=0,width=1100,height=600,toolbar=0,scrollbars=0,status=0');
                WinPrint.document.write('<link href="../css/bootstrap.css" rel="stylesheet">');
                WinPrint.document.write(prtContent.innerHTML);
                WinPrint.document.close();
                WinPrint.focus();
                WinPrint.print();
                WinPrint.close();
            }
        </script>
    </div>
    </body>
    </html>


<?php

?>