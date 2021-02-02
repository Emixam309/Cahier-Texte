<?php
if (isset($_POST['commentaire'])) {
    $query = 'INSERT INTO commentaires (idUser, commentaire) VALUES (?, ?)';
    $stmt = $bdd->prepare($query);
    $stmt->bind_param("is", $_SESSION['idUser'], $_POST['commentaire']);
    if (!$stmt->execute()) {
        printf("Erreur : %s\n", $stmt->error);
        $alertFail = "Le commentaire n'a pas pu être posté";
    } else {
        $alertSuccess = "Le commentaire a bien été posté.";
    }
}
if (isset($_POST['del-com'])) {
    if (!$bdd->query('DELETE FROM commentaires WHERE idCommentaire = ' . $_POST['del-com']))
        printf("Erreur : %s\n", $bdd->error);
}
?>
<div>
    <h2 class="mb-3 text-center">Commentaires</h2>
    <div class="row">
        <div class="col-md-4 mx-auto text-center mb-3">
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
            <form action="" method="post">
                <h3 class="mb-3">Ajouter un commentaire</h3>
                <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Commentaire" name="commentaire"
                                  style="height: 100px" maxlength="255" required></textarea>
                    <label for="commentaire">Commentaire</label>
                </div>
                <input class="btn btn-primary" type="submit" value="Publier">
            </form>
        </div>
        <div class="col-md-6 mx-auto" style="height: 50vh; width: 60%">
            <h3 class="mb-2 text-center">Commentaires de la semaine</h3>
            <div class="overflow-auto border p-2 rounded h-100 d-inline-block shadow-lg"
                 style="width: 100%; background: linear-gradient(#3094bf, #0035ca);">
                <?php
                if (isset($_GET['formation'])) {
                    $query = $bdd->query('SELECT idCommentaire, users.idUser, nom, prenom, commentaire, dateHeure FROM commentaires
                INNER JOIN users ON commentaires.idUser = users.idUser WHERE dateHeure > DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND idFormation = '. $_GET['formation'].' ORDER BY dateHeure DESC');
                } else {
                    $query = $bdd->query('SELECT idCommentaire, users.idUser, nom, prenom, commentaire, dateHeure FROM commentaires
                INNER JOIN users ON commentaires.idUser = users.idUser WHERE dateHeure > DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND idFormation IS NULL ORDER BY dateHeure DESC');
                }
                while ($resultat = $query->fetch_object()) { ?>
                    <div class="border p-1 my-2 rounded" style="background-color: white">
                        <div class="mx-2">
                            <h6><?php echo $resultat->nom . ' ' . $resultat->prenom ?></h6>
                            <p><?php echo $resultat->commentaire ?></p>
                            <?php
                            $date = strtotime($resultat->dateHeure);
                            $date = date('d/m/Y - H:i', $date); ?>
                            <div class="row">
                                <div class="col-6">
                                    <?php echo $date ?>
                                </div>
                                <div class="col-6 text-end">
                                    <?php if (isset($_SESSION['admin']) or $_SESSION['idUser'] == $resultat->idUser) { ?>
                                        <form action="" method="post"
                                              id="del-com-<?php echo $resultat->idCommentaire ?>">
                                            <input type="hidden" value="<?php echo $resultat->idCommentaire ?>" name="del-com">
                                        </form>
                                        <a href="#"
                                           onclick="document.getElementById('del-com-<?php echo $resultat->idCommentaire; ?>').submit()">Supprimer</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>