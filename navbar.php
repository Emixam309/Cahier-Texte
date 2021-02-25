<?php $pageName = basename($_SERVER['PHP_SELF']); ?>
<script type="text/javascript" src="js/bootstrap.js"></script>
<nav class="navbar navbar-expand-lg navbar-dark mb-4 shadow" style="background: linear-gradient(#0031ba, #004ca9);">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img class="rounded d-inline-block align-top" src="img/arep.png" width="32">
            Cahier de texte</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php
                    if ($pageName == 'compte-rendu.php') echo "active"; ?>" href="compte-rendu.php">Cahier de Texte</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php
                    if ($pageName == 'cr-date.php') echo "active"; ?>" href="cr-date.php">Date</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php
                    if ($pageName == 'cr-formations.php' OR $pageName == 'cr-formations-all.php') echo "active"; ?>" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Formations
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="cr-formation.php">Vous</a></li>
                        <li><a class="dropdown-item" href="cr-formation-all.php">Tous les Formateurs</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php
                    if ($pageName == 'evaluation.php') echo "active"; ?>" href="evaluation.php">Évaluation</a>
                </li>
            </ul>
            <?php if (isset($_SESSION['admin'])) { ?>
                <div class="navbar-nav">
                    <a class="nav-link active" href="admin">Administration</a>
                </div>
            <?php } ?>
            <span class="navbar-text">Bonjour, <?php echo $_SESSION['prenom'] . " " . $_SESSION['nom']; ?></span>
            <div class="navbar-nav">
                <a class="nav-link active" href="logout.php">Déconnexion</a>
            </div>
        </div>
    </div>
</nav>