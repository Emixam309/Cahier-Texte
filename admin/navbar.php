<?php $pageName = basename($_SERVER['PHP_SELF']);
?>
<script type="text/javascript" src="../js/bootstrap.js"></script>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow">
    <div class="container-xl">
        <a class="navbar-brand" href="index.php">
            Cahier de texte - Administration</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php
                    if ($pageName == 'formations.php' OR $pageName == 'modules.php' OR $pageName == 'promotions.php' OR $pageName == 'stagiaires.php') echo "active"; ?>" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Formations
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="formations.php">Créations</a></li>
                        <li><a class="dropdown-item" href="modules.php">Modules</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="promotions.php">Promotions</a></li>
                        <li><a class="dropdown-item" href="stagiaires.php">Stagiaires</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php
                    if ($pageName == 'formateurs.php' OR $pageName == 'affectation.php') echo "active"; ?>" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Formateurs
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="formateurs.php">Créations</a></li>
                        <li><a class="dropdown-item" href="affectation.php">Affectation</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php
                    if ($pageName == 'cr-formations.php' OR $pageName == 'cr-module.php' OR $pageName == 'cr-formateur.php') echo "active"; ?>" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Compte Rendu
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="cr-formation.php">Formation</a></li>
                        <li><a class="dropdown-item" href="cr-module.php">Module</a></li>
                        <li><a class="dropdown-item" href="cr-formateur.php">Formateur</a></li>
                    </ul>
                </li>
            </ul>
            <span class="navbar-text">Bonjour, <?php echo $_SESSION['prenom'] . " " . $_SESSION['nom']; ?></span>
            <div class="navbar-nav">
                <a class="nav-link active" href="../logout.php">Déconnexion</a>
            </div>
        </div>
    </div>
</nav>