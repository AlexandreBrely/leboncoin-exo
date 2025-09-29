<?php require_once __DIR__ . '/partials/header.php'; ?>
<?php require_once __DIR__ . '/partials/navbar.php'; ?>

<div class="container mt-5">
    <h2>Modifier l'annonce</h2>
    <div class="row">
        <div class="col-md-6">
            <img src="uploads/<?= htmlspecialchars($annonce['a_picture']) ?>" class="img-fluid" alt="Image annonce">
        </div>
        <div class="col-md-6">
            <form method="POST" enctype="multipart/form-data">
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
                <div class="mb-3">                 
                    <label for="image" class="form-label">Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                     <input type="hidden" name="old_picture" value="<?= htmlspecialchars($annonce['a_picture']) ?>">
                </div>
                <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
                <a href="index.php?url=profil" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>