<?php $pageName = basename($_SERVER['PHP_SELF']); ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">Cahier de texte - Administration</a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php
                    if ($pageName == 'formateurs.php') echo "active"; ?>" href="formateurs.php">Formateurs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php
                    if ($pageName == 'formations.php') echo "active"; ?>" href="formations.php">Formations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php
                    if ($pageName == 'modules.php') echo "active"; ?>" href="modules.php">Modules</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php
                    if ($pageName == 'affectation.php') echo "active"; ?>" href="affectation.php">Affectation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php
                    if ($pageName == 'export.php') echo "active"; ?>" href="export.php">Export</a>
                </li>
            </ul>
            <span class="navbar-text">Bonjour, <?php echo $_SESSION['prenom'] . " " . $_SESSION['nom']; ?></span>
            <div class="navbar-nav">
                <a class="nav-link active" href="../logout.php">Déconnexion</a>
            </div>
        </div>
    </div>
    </div>
</nav>