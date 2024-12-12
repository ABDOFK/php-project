<?php
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function enregistrer($nom, $email, $mot_de_passe) {
        $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);
        $sql = "INSERT INTO utilisateurs (nom, email, mot_de_passe) 
                VALUES (:nom, :email, :mot_de_passe)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':email' => $email,
            ':mot_de_passe' => $mot_de_passe_hash
        ]);
        return $this->pdo->lastInsertId();
    }

    public function connecter($email, $mot_de_passe) {
        $sql = "SELECT * FROM utilisateurs WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
            return $user;
        }

        return false;
    }
}
?>
