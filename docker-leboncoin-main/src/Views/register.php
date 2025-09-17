
<?php require_once __DIR__ . '/partials/header.php'; ?>
<?php require_once __DIR__ . '/partials/navbar.php'; ?>

<?php $errors = $errors ?? [];
$old = $old ?? []; ?>

<main class="container mt-5">
    <h2 class="mb-4">Créer un compte</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $key => $msg): ?>
                    <li><?= htmlspecialchars($msg) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="index.php?url=register" method="POST" novalidate>
        <div class="mb-3">
            <label for="username" class="form-label">Pseudo</label><span class="ms-2 text-danger fst-italic fw-light"><?= $errors["username"] ?? '' ?></span>
            <input type="text" class="form-control" id="username" name="username" required value="<?= htmlspecialchars($old['username'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Adresse email</label><span class="ms-2 text-danger fst-italic fw-light"><?= $errors["email"] ?? '' ?></span>
            <input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($old['email'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label><span class="ms-2 text-danger fst-italic fw-light"><?= $errors["password"] ?? '' ?></span>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-warning">S'inscrire</button>

        <a class="d-block mt-2" href="index.php?url=login">Déjà inscrit ? Je me connecte !</a>
        
    </form>
</main>

<?php require_once __DIR__ . '/partials/footer.php'; ?>