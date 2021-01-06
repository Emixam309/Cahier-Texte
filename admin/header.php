<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">Cahier de texte - Administration</a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="formateurs.php">Formateurs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="formations.php">Formations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="modules.php">Modules</a>
                </li>
            </ul>
            <span class="navbar-text">Bonjour, <?php echo $_SESSION['prenom'] . " " . $_SESSION['nom']; ?></span>
            <a class="nav-link" href="../logout.php">Déconnexion</a>
        </div>
    </div>
    </div>
</nav>