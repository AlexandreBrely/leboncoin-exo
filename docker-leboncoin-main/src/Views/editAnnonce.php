<?php require_once __DIR__ . '/partials/header.php'; ?>
<?php require_once __DIR__ . '/partials/navbar.php'; ?>

<div class="container mt-5">
    <h2>Modifier l'annonce</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($annonce['a_title']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="4"><?= htmlspecialchars($annonce['a_description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Prix (€)</label>
            <input type="number" class="form-control" id="price" name="price" value="<?= htmlspecialchars($annonce['a_price']) ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
        <a href="index.php?url=profil" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>