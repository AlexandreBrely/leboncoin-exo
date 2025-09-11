<?php require_once __DIR__ . '/partials/header.php'; ?>
<?php require_once __DIR__ . '/partials/navbar.php'; ?>

<main class="container mt-5">
    <h2><?= htmlspecialchars($annonce['a_title']) ?></h2>
    <div class="row">
        <div class="col-md-6">
            <img src="public/uploads/<?= htmlspecialchars($annonce['a_picture']) ?>" class="img-fluid" alt="Image annonce">
        </div>
        <div class="col-md-6">
            <p><strong>Prix :</strong> <?= htmlspecialchars($annonce['a_price']) ?> €</p>
            <p><strong>Description :</strong><br><?= nl2br(htmlspecialchars($annonce['a_description'])) ?></p>
            <p><strong>Publié le :</strong> <?= htmlspecialchars($annonce['a_publication']) ?></p>
            <p><strong>Vendeur :</strong> <?= htmlspecialchars($annonce['u_username']) ?></p>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>