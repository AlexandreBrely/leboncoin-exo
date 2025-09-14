<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="index.php?url=home">Plastic Crack Market</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item">
                        <span class="nav-link text-warning">Bienvenue <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?url=create">Déposer une annonce</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?url=profil">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?url=logout">Déconnexion</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?url=annonces">Annonces</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?url=register">Inscription</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?url=login">Connexion</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>