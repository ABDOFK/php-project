<?php
require_once 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    try {
        $user->enregistrer($nom, $email, $mot_de_passe);
        $message = "Compte créé avec succès. Vous pouvez maintenant vous connecter.";
    } catch (Exception $e) {
        $message = "Erreur lors de l'inscription : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="styles/inscription.css">
    <style>

    </style>
</head>

<body>
    <div class="container">
        <h1>Inscription</h1>
        <form method="post">
            <label>Nom :</label>
            <input type="text" name="nom" required>
            <label>Email :</label>
            <input type="email" name="email" required>
            <label>Mot de passe :</label>
            <input type="password" name="mot_de_passe" required>
            <button type="submit">S'inscrire</button>
        </form>
        <?php if (isset($message)) echo "<p>$message</p>"; ?>
        <a href="index.php">Retour à la Connexion</a>
    </div>
</body>

</html>