<?php require_once __DIR__ . '/partials/header.php'; ?>
<?php require_once __DIR__ . '/partials/navbar.php'; ?>

<main class="container mt-5">

    <div class="container text-center mt-5">
        <h1 class="display-4">Plastic Crack Market</h1>
        <p class="lead">Le marché dédié aux hobbyistes, peintres et collectionneurs de figurines.</p>
        <a href="index.php?url=create" class="btn btn-warning btn-lg">Déposer une annonce</a>
    </div>

    <h2 class="my-4 text-center">Toutes les annonces</h2>
    <?php if (empty($annonces)): ?>
        <p class="text-center">Aucune annonce disponible pour le moment.</p>
    <?php else: ?>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            <?php foreach ($annonces as $annonce): ?>

                <div class="col-md-3">
                    <div class="card h-100 d-flex flex-column">
                        <div class="card-header text-center">
                            <h5 class="card-title"><?= htmlspecialchars($annonce['a_title']) ?></h5>
                        </div>
                        <img src="/uploads/<?= htmlspecialchars($annonce['a_picture']) ?>" class="card-img-top" alt="Image annonce">
                        <div class="card-body d-flex flex-column text-center">
                            <p class="card-text"><?= htmlspecialchars($annonce['a_price']) ?> €</p>
                            <a href="index.php?url=details/<?= $annonce['a_id'] ?>" class="btn btn-warning">Voir l'annonce</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>