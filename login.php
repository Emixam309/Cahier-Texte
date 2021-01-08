<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
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
            header("Location: admin/index.php");
        }
    } else {
        $message = "Le nom d'utilisateur ou le mot de passe est incorrect.";
    }
}
?>
<form class="box" action="" method="post" name="login">
    <table class="box">
        <td>
            <img class="box-img" src="arep.png" height="230px"></td>
        <td>
            <h1 class="box-title">Connexion</h1>
            <input type="text" class="box-input" name="username" placeholder="Nom d'utilisateur" required>
            <input type="password" class="box-input" name="password" placeholder="Mot de passe" required>
            <input type="submit" value="Connexion" name="submit" class="box-button">
            <?php if (!empty($message)) { ?>
                <p class="errorMessage"><?php echo $message; ?></p>
            <?php } ?>
        </td>
    </table>
</form>
</body>
</html>