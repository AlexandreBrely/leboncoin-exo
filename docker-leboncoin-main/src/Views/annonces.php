<?php require_once __DIR__ . '/partials/header.php'; ?>
<?php require_once __DIR__ . '/partials/navbar.php'; ?>

<main class="container mt-5">

    <div class="container text-center mt-5">
        <h1 class="display-4">Plastic Crack Market</h1>
        <p class="lead">Le marché dédié aux hobbyistes, peintres et collectionneurs de figurines.</p>
        <a href="index.php?url=create" class="btn btn-warning btn-lg">Déposer une annonce</a>
    </div>

    <h2 class="mb-4">Toutes les annonces</h2>
    <?php if (empty($annonces)): ?>
        <p class="text-center">Aucune annonce disponible pour le moment.</p>
    <?php else: ?>

        <div class="row">
            <?php foreach ($annonces as $annonce): ?>
                
                <div class="col-md-4">
                    <div class="card card-image h-100">
                        <img src="/uploads/<?= htmlspecialchars($annonce['a_picture']) ?>" class="card-img-top" alt="Image annonce">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($annonce['a_title']) ?></h5>
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