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
<body class="container" style="background: #d9d9d9">
<?php
require('config.php');
session_start();
if (isset($_POST['username'])) {
    $username = stripslashes($_REQUEST['username']);
    //$username = mysqli_real_escape_string($bdd, $username);
    $password = stripslashes($_REQUEST['password']);
    //$password = mysqli_real_escape_string($bdd, $password);
    $query = "SELECT * FROM users WHERE username = '$username' and password = '$password'";
    $result = mysqli_query($bdd, $query) or die(mysqli_error($bdd));
    $rows = mysqli_num_rows($result);
    if ($rows == 1) {
        $_SESSION['username'] = $username;
        $result = mysqli_fetch_object($result);
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
<form class="position-absolute top-50 start-50 translate-middle p-5 rounded shadow" style="background: white"
      action="login.php" method="post" name="login">
    <div class="row">
        <div class="col-md-auto mx-auto">
            <img class="position-relative top-50 start-50 translate-middle rounded" src="arep.png"></div>
        <div class="col-md-auto mx-auto">
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