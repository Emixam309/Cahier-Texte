<?php
// Initialiser la session
session_start();
// Vérifiez si l'utilisateur est connecté et administrateur, sinon redirigez-le vers la page de connexion
//if (!isset($_SESSION["username"]) or !isset($_SESSION['admin'])) {
//header("Location: ../index.php");
//exit();
//}

require('../config.php');
if (isset($_POST['formateur'])) {
    $query = 'INSERT INTO users (nom,prenom,mail,username,password) VALUES (?, ?, ?, ?, ?)';
    $stmt = $bdd->prepare($query);
    $stmt->bind_param("sssss", $_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['id'], $_POST['password']);
    if (!$stmt->execute()) {
        printf("Erreur : %s\n", $stmt->error);
        $message = "Le formateur n'a pas pu être créé.";
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
<?php include("header.php");
$query = $bdd->query('SELECT username, nom, prenom, mail FROM users WHERE admin != 1');
?>
<div class="container">
    <div class="row"> <!--Tableau de la liste des Formateurs-->
        <div class="col-md-5">
            <h1 class="text-center mb-4">Liste des formateurs</h1>
            <table class="table table-striped border border-3">
                <thead>
                <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Prenom</th>
                    <th scope="col">Mail</th>
                </tr>
                </thead>
                <tbody>
                <?php
                echo "<tr>";
                while ($resultat = $query->fetch_object()) {
                    echo "<td>" . $resultat->nom . "</td>";
                    echo "<td>" . $resultat->prenom . "</td>";
                    echo "<td>" . $resultat->mail . "</td>";
                }
                echo "</tr>"; ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-7 text-center"> <!--Formulaire de création d'un formateur-->
            <h1 class="mb-4">Création d'un formateur</h1>
            <form class="mx-auto mb-3" style="width: 60%" action="" method="post" name="formateur">
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
                    <input class="form-control" name="id" type="text" placeholder="Identifiant">
                    <label for="id">Identifiant</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" name="password" type="password" placeholder="Mot de passe" required>
                    <label for="password">Mot de passe</label>
                </div>
                <input class="btn btn-primary" type="submit" value="Créer" name="formateur">
            </form>
            <?php if (!empty($message)) { ?>
                <p class="errorMessage"><?php echo $message; ?></p>
            <?php } ?>
        </div>
    </div>
</div>
</body>
</html>