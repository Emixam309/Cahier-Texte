<?php include("session.php");
if (isset($_POST['formateur'])) {
    $query = 'INSERT INTO users (username, password, nom, prenom, mail, telephone) VALUES (?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE password=?, nom=?, prenom=?, mail=?, telephone=?';
    $stmt = $bdd->prepare($query);
    $stmt->bind_param("sssssssssss", $_POST['id'], $_POST['password'], $_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['telephone'], $_POST['password'], $_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['telephone']);
    if (!$stmt->execute()) {
        printf("Erreur : %s\n", $stmt->error);
        $alertFail = "Le formateur n'a pas pu être ajouté ou modifié.";
    } else {
        $alertSuccess = "Le formateur a bien été ajouté ou modifié.";
    }
}
$title = "Ajout d'un formateur";
if (isset($_POST['edit-username'])) {
    $query = $bdd->query('SELECT * FROM users WHERE username = "' . $_POST['edit-username'] . '"');
    $resultEdit = $query->fetch_object();
    $query->close();
    $title = "Modifier : " . $resultEdit->nom . " " . $resultEdit->prenom;
    $button = "Modifier";
}
if (isset($_POST['del-username'])) {
    if (!$bdd->query('DELETE FROM users WHERE username = "' . $_POST['del-username'] . '"')) {
        $alertDelFail = "Le formateur n'a pas pu être supprimé.";
    } else {
        $alertDelSuccess = "Le formateur a bien été supprimé.";
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
    <title>Formateurs</title>
</head>
<body>
<?php include("navbar.php"); ?>
<div class="container">
    <div class="row">
        <div class="col-xl-auto mx-auto text-center"> <!--Formulaire de création d'un formateur-->
            <h1 class="mb-4"><?php echo $title ?></h1>
            <form class="mb-3" action="" method="post" name="formateur">
                <div class="row mb-3">
                    <div class="col-md">
                        <div class="form-floating">
                            <input class="form-control" name="nom" type="text"
                                   placeholder="Nom"
                                   required <?php if (isset($_POST['edit-username'])) echo 'value="' . $resultEdit->nom . '"' ?>>
                            <label for="nom">Nom *</label>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            <input class="form-control" name="prenom" type="text"
                                   placeholder="Prenom"
                                   required <?php if (isset($_POST['edit-username'])) echo 'value="' . $resultEdit->prenom . '"' ?>>
                            <label for="prenom">Prenom *</label>
                        </div>
                    </div>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" name="id" type="text" placeholder="Identifiant"
                           required <?php if (isset($_POST['edit-username'])) echo 'readonly value="' . $resultEdit->username . '"' ?>>
                    <label for="id">Identifiant *</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" name="password" type="password" placeholder="Mot de passe"
                           required <?php if (isset($_POST['edit-username'])) echo 'value="' . $resultEdit->password . '"' ?>>
                    <label for="password">Mot de passe *</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" name="email" type="email"
                           placeholder="exemple@exemple.com" <?php if (isset($_POST['edit-username'])) echo 'value="' . $resultEdit->mail . '"' ?>>
                    <label for="email">Mail</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" name="telephone" type="tel" placeholder="Numéro de téléphone"
                           pattern="[0-9]{10}"
                           maxlength="10" <?php if (isset($_POST['edit-username'])) echo 'value="' . $resultEdit->telephone . '"' ?>>
                    <label for="email">Téléphone</label>
                </div>
                <p>Les champs indiqués par une * sont obligatoires</p>
                <input class="btn btn-primary" type="submit" value="<?php echo $button ?>" name="formateur">
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
            <h1 class="text-center mb-4">Liste des formateurs</h1>
            <?php if (!empty($alertDelFail)) {
                echo '<div class="mt-3 alert alert-danger text-center">'
                    . $alertDelFail .
                    '</div>';
            } elseif (!empty($alertDelSuccess)) {
                echo '<div class="mt-3 alert alert-success text-center">'
                    . $alertDelSuccess .
                    '</div>';
            } ?>
            <!--Tableau de la liste des Formateurs-->
            <table class="table table-striped border border-3 text-center">
                <thead>
                <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Prenom</th>
                    <th scope="col">Identifiant</th>
                    <th scope="col">Mail</th>
                    <th scope="col">Téléphone</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $query = $bdd->query('SELECT * FROM users WHERE admin != 1 GROUP BY nom, prenom');
                while ($resultat = $query->fetch_object()) {
                    echo "<tr>";
                    echo "<td>" . $resultat->nom . "</td>";
                    echo "<td>" . $resultat->prenom . "</td>";
                    echo "<td>" . $resultat->username . "</td>";
                    echo '<td><a class="link-dark" href="mailto:' . $resultat->mail . '">' . $resultat->mail . '</a></td>';
                    echo "<td>" . $resultat->telephone . "</td>"; ?>
                    <td>
                        <a href="#"
                           onclick="document.getElementById('edit-user-<?php echo $resultat->username; ?>').submit()">Modifier</a>
                        <a href="#"
                           onclick="document.getElementById('del-user-<?php echo $resultat->username; ?>').submit()">Supprimer</a>
                    </td> <?php
                    echo '<form action="" method="post" id="edit-user-' . $resultat->username . '">';
                    echo '<input hidden value="' . $resultat->username . '" name="edit-username">';
                    echo '</form>';
                    echo '<form action="" method="post" id="del-user-' . $resultat->username . '">';
                    echo '<input hidden value="' . $resultat->username . '" name="del-username">';
                    echo '</form>';
                } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>