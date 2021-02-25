<?php include("session.php");
if (isset($_POST['compte-rendu'])) {
    $query = 'UPDATE compterendu SET idModule=?, idPromo=?, idUser=?, date=?, duree=?, distanciel=?, contenu=?, moyen=?, objectif=?, evaluation=?
              WHERE idCompteRendu =' . $_POST['idCR'];
    $stmt = $bdd->prepare($query);
    $stmt->bind_param("iiisiissss", $_POST['module'], $_POST['promotion'], $_SESSION['idUser'], $_POST['date'], $_POST['duree'],
        $_POST['distanciel'], $_POST['contenu'], $_POST['moyen'], $_POST['objectif'], $_POST['evaluation']);
$stmt->execute();
header("Location: index.php");
}
if (isset($_POST['idCR'])) {
    $query = $bdd->query('SELECT * FROM compterendu WHERE idCompteRendu = "' . $_POST['idCR'] . '"');
    $resultEdit = $query->fetch_object();
    $query->close();
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/bootstrap.css" rel="stylesheet">
    <titleCahier de Texte/title>
</head>
<body>
<?php include("navbar.php") ?>
<div class="container">
    <h1 class="text-center mb-3">Modifier le Compte Rendu de la séance</h1>
    <div class="row">
        <div class="col-lg-auto ms-auto">
            <form class="" action="" method="post" name="compte-rendu">
                <div class="form-floating mb-3">
                    <input class="form-control" name="date"
                           type="date" <?php if (isset($_POST['idCR'])) echo 'value="' . $resultEdit->date . '"' ?>
                           required>
                    <label for="date">Date</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" name="duree" type="number"
                           placeholder="Durée" <?php if (isset($_POST['idCR'])) echo 'value="' . $resultEdit->duree . '"' ?>>
                    <label for="duree">Durée de la séance</label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="distanciel" name="distanciel"
                           value="1" <?php if (isset($_POST['idCR'])) if (isset($resultEdit->distanciel)) echo "checked" ?>>
                    <label class="form-check-label" for="distanciel">Distanciel</label>
                </div>
        </div>
        <div class="col-lg-5 me-auto">
            <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Contenu de la séance" name="contenu"
                                  style="height: 100px"
                                  maxlength="255"><?php if (isset($_POST['idCR'])) echo $resultEdit->contenu ?></textarea>
                <label for="contenu">Contenu de la séance</label>
            </div>
            <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Moyens techniques et pédagogiques" name="moyen"
                                  style="height: 100px"
                                  maxlength="255"><?php if (isset($_POST['idCR'])) echo $resultEdit->moyen ?></textarea>
                <label for="moyen">Moyens techniques et pédagogiques</label>
            </div>
            <div class="form-floating mb-3">
                <textarea class="form-control" placeholder="Objectif Ciblé" name="objectif"
                          maxlength="255"><?php if (isset($_POST['idCR'])) echo $resultEdit->objectif ?></textarea>
                <label for="objectif">Objectif ciblé (référentiel)</label>
            </div>
            <div class="form-floating mb-3">
                <textarea class="form-control" placeholder="Evaluation" name="evaluation"
                          maxlength="250"><?php if (isset($_POST['idCR'])) echo $resultEdit->evaluation ?></textarea>
                <label for="evaluation">Evaluation (si il y a, indiquer le libellé)</label>
            </div>
        </div>
        <div class="text-center">
            <input type="hidden" value="<?php echo $_POST['idCR'] ?>" name="idCR">
            <input type="hidden" value="<?php echo $_POST['promotion'] ?>" name="promotion">
            <input type="hidden" value="<?php echo $_POST['module'] ?>" name="module">
            <input class="btn btn-primary" type="submit" value="Modifier" name="compte-rendu">
        </div>
    </div>
</div>
</body>
</html>