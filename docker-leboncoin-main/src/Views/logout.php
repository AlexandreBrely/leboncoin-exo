<?php
// session_start();
// var_dump($_SESSION);
session_unset();
session_destroy();
?>

<?php require_once __DIR__ . '/partials/header.php'; ?>
<?php require_once __DIR__ . '/partials/navbar.php'; ?>

<div class="container text-center mt-5">
    <h2>Vous êtes bien déconnecté.</h2>
    <p>Redirection vers l'accueil dans quelques secondes...</p>
</div>

<script>
    setTimeout(function() {
        window.location.href = "index.php?url=home";
    }, 3000); // 3 secondes
</script>

<?php require_once __DIR__ . '/partials/footer.php'; ?>