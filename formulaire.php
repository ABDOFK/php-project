<?php
require_once 'connexion.php';
session_start();

if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'client') {
    header('Location: afficher.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'];
    $priorite = $_POST['priorite'];

    $reclamation->ajouter($_SESSION['utilisateur']['id'], $description, $priorite);
    $message = "Réclamation ajoutée avec succès !";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Nouvelle Réclamation</title>
    <link rel="stylesheet" href="styles/formulaire.css">

</head>

<body>
    <div class="container">
        <h1>Nouvelle Réclamation</h1>
        <form method="post">
            <label>Description :</label>
            <textarea name="description" required></textarea>
            <label>Priorité :</label>
            <select name="priorite" required>
                <option value="basse">Basse</option>
                <option value="moyenne">Moyenne</option>
                <option value="haute">Haute</option>
            </select>
            <button type="submit">Soumettre</button>
        </form>
        <?php if (isset($message)) echo "<p>$message</p>"; ?>
        <a href="afficher.php">Voir mes réclamations</a>
        <a href="logout.php" class="btn btn-danger">Déconnexion</a>
    </div>
</body>

</html>