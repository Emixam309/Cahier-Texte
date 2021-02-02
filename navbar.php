<?php $pageName = basename($_SERVER['PHP_SELF']); ?>
<script type="text/javascript" src="js/bootstrap.js"></script>
<nav class="navbar navbar-expand-lg navbar-dark mb-4 shadow" style="background-color: #0024c8;">
    <div class="container">
        <a class="navbar-brand" href="index.php">Cahier de texte</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php
                    if ($pageName == 'compte-rendu.php') echo "active"; ?>" href="compte-rendu.php">Compte Rendu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php
                    if ($pageName == 'cr-date.php') echo "active"; ?>" href="cr-date.php">Date</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php
                    if ($pageName == 'cr-formation.php') echo "active"; ?>" href="cr-formation.php">Formation</a>
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
    </div>
</nav>