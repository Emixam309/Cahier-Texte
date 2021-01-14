<?php include("session.php");

if (isset($_POST['formateur'])) {
    $query = 'INSERT INTO users (username, password, nom, prenom, mail, telephone) VALUES (?, ?, ?, ?, ?, ?)';
    $stmt = $bdd->prepare($query);
    $stmt->bind_param("ssssss", $_POST['id'], $_POST['password'], $_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['telehpone']);
    if (!$stmt->execute()) {
        printf("Erreur : %s\n", $stmt->error);
        $erreur = "Le formateur n'a pas pu être ajouté.";
    } else {
        $message = "Le formateur à bien été ajouté.";
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
<?php include("header.php"); ?>
<div class="container">
    <div class="row"> <!--Tableau de la liste des Formateurs-->
        <div class="col-xl-auto mx-auto text-center"> <!--Formulaire de création d'un formateur-->
            <h1 class="mb-4">Création d'un formateur</h1>
            <form class="mb-3" action="" method="post" name="formateur">
                <div class="row mb-3">
                    <div class="col-md">
                        <div class="form-floating">
                            <input class="form-control" name="nom" type="text" placeholder="Nom">
                            <label for="nom">Nom</label>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            <input class="form-control" name="prenom" type="text" placeholder="Prenom">
                            <label for="prenom">Prenom</label>
                        </div>
                    </div>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" name="email" type="email" placeholder="exemple@exemple.com">
                    <label for="email">Mail</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" name="telephone" type="tel" placeholder="Numéro de téléphone"
                           pattern="[0-9]{10}" maxlength="10">
                    <label for="email">Téléphone</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" name="id" type="text" placeholder="Identifiant" required>
                    <label for="id">Identifiant</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" name="password" type="password" placeholder="Mot de passe" required>
                    <label for="password">Mot de passe</label>
                </div>
                <input class="btn btn-primary" type="submit" value="Créer" name="formateur">
            </form>
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
        </div>
        <div class="col-xl-auto mx-auto">
            <h1 class="text-center mb-4">Liste des formateurs</h1>
            <table class="table table-striped border border-3">
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
                    echo "<td><a href='mailto:'" . $resultat->mail . ">" . $resultat->mail . "</a></td>";
                    echo "<td>" . $resultat->telephone . "</td>";
                    echo "<td><a onclick=''>Supprimer</a>" . "</td>";
                    echo "</tr>";
                } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>