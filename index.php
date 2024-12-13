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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 16px;
            margin-bottom: 5px;
            color: #555;
        }

        input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
        }

        button {
            padding: 10px;
            background-color:rgb(33, 141, 184);
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color:rgb(25, 141, 164);
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        a {
            text-align: center;
            display: block;
            margin-top: 20px;
            color:rgba(20, 164, 189, 0.76);
            text-decoration: none;
            font-size: 16px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
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
