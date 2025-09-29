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
                        <img src="uploads/<?= htmlspecialchars($annonce['a_picture']) ?>" class="card-img-top" alt="Image annonce">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($annonce['a_title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($annonce['a_price']) ?> €</p>
                            <a href="index.php?url=details/<?= $annonce['a_id'] ?>" class="btn btn-warning">Voir</a>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="index.php?url=edit-annonce&id=<?= $annonce['a_id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                            <form action="index.php?url=delete-annonce/<?= $annonce['a_id'] ?>"
                                method="POST"
                                class="m-0 p-0"
                                onsubmit="return confirm('Supprimer cette annonce ?')">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Supprimer
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>