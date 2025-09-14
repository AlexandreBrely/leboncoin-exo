<?php require_once __DIR__ . '/partials/header.php'; ?>
<?php require_once __DIR__ . '/partials/navbar.php'; ?>

<main class="container mt-5">
    <h2>Profil de <?= htmlspecialchars($_SESSION['user']['username']) ?></h2>
    <p>Email : <?= htmlspecialchars($_SESSION['user']['email']) ?></p>

    <h4 class="mt-4">Mes annonces</h4>

    <?php if (empty($annonces)): ?>
        <p>Aucune annonce publiée pour le moment.</p>
    <?php else: ?>
        <div class="row">
            <?php foreach ($annonces as $annonce): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="public/uploads/<?= htmlspecialchars($annonce['a_picture']) ?>" class="card-img-top" alt="Image annonce">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($annonce['a_title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($annonce['a_price']) ?> €</p>
                            <a href="index.php?url=details/<?= $annonce['a_id'] ?>" class="btn btn-warning">Voir</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>