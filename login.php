<?php
require('config.php');
session_start();
if (isset($_POST['username'])) {
    $username = stripslashes($_REQUEST['username']);
    $username = mysqli_real_escape_string($bdd, $username);
    $password = stripslashes($_REQUEST['password']);
    $password = mysqli_real_escape_string($bdd, $password);
    $query = "SELECT * FROM users WHERE username = '$username' and password = '$password'";
    $result = mysqli_query($bdd, $query) or die(mysqli_error($bdd));
    $rows = mysqli_num_rows($result);
    if ($rows == 1) {
        $_SESSION['username'] = $username;
        $result = mysqli_fetch_object($result);
        $_SESSION['idUser'] = $result->idUser;
        $_SESSION['prenom'] = $result->prenom;
        $_SESSION['nom'] = $result->nom;
        if ($result->admin != 1) {
            header("Location: index.php");
        } else {
            $_SESSION['admin'] = $result->admin;
            header("Location: admin");
        }
    } else {
        $message = "L'identifiant ou le mot de passe est incorrect.";
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
    <title>Connexion</title>
    <link rel="stylesheet" href="css/bootstrap.css">
</head>
<style type="text/css">
    body {
        background-image: url("img/stjo.jpg");
        background-repeat: no-repeat;
        background-size: cover;
    }
</style>
<body>
<form class="position-absolute top-50 start-50 translate-middle p-5 rounded shadow" style="background: white"
      action="login.php" method="post" name="login">
    <div class="row">
        <div class="col-md-auto mx-auto">
            <img class="rounded" src="img/arep-full.png" width="250">
            <div class="row g-0">
                <div class="col-md-auto">
                    <img src="img/certfication-qualite-bureau-veritas.jpg" width="125px">
                </div>
                <div class="col-md-auto">
                    <img class="position-relative top-50 translate-middle-y" src="img/Logo-Qualiopi.png" width="125px">
                </div>
                            </div>
        </div>
        <div class="col-md-auto mx-auto my-auto">
            <h1 class="mb-3 text-center">Connexion</h1>
            <div class="mb-3">
                <div class="form-floating">
                    <input class="form-control" name="username" type="text" placeholder="Identifiant">
                    <label for="reference">Identifiant</label>
                </div>
            </div>
            <div class="mb-3">
                <div class="form-floating">
                    <input class="form-control" name="password" type="password" placeholder="Mot de Passe">
                    <label for="reference">Mot de Passe</label>
                </div>
            </div>
            <div class="text-center">
                <input class="btn btn-primary" type="submit" value="Connexion" name="submit">
            </div>
        </div>
    </div>

    <?php if (!empty($message)) { ?>
        <div class="mt-3 alert alert-danger text-center" style="margin-bottom: 0">
            <?php echo $message; ?>
        </div>
    <?php }elseif (isset($_GET['timeout'])) {
        echo '<div class="mt-3 alert alert-secondary text-center" style="margin-bottom: 0">Vous avez été déconnecté</div>';
        } ?>
</form>
</body>
</html>