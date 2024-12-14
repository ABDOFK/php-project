<?php
require_once 'connexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $utilisateur = $user->connecter($email, $mot_de_passe);
    if ($utilisateur) {
        $_SESSION['utilisateur'] = $utilisateur;
        header('Location: formulaire.php');
        exit();
    } else {
        $erreur = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="styles/index.css">
</head>

<body>

    <div class="container">
        <h1>Connexion</h1>
        <form method="post">
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" required>

            <label for="mot_de_passe">Mot de passe :</label>
            <input type="password" name="mot_de_passe" id="mot_de_passe" required>

            <button type="submit">Se connecter</button>
        </form>

        <?php if (isset($erreur)) : ?>
            <p class="error"><?= $erreur; ?></p>
        <?php endif; ?>

        <a href="inscription.php">Créer un compte</a>
    </div>

</body>

</html>