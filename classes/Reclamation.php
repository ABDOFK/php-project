<?php
class Reclamation
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }


    public function ajouter($utilisateur_id, $description, $priorite)
    {
        $sql = "INSERT INTO reclamations (utilisateur_id, description, priorite, statut, date_creation) 
                VALUES (:utilisateur_id, :description, :priorite, 'ouverte', NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':utilisateur_id' => $utilisateur_id,
            ':description' => $description,
            ':priorite' => $priorite,
        ]);
    }


    public function listerPourClient($utilisateur_id)
    {
        $sql = "SELECT * FROM reclamations WHERE utilisateur_id = :utilisateur_id ORDER BY date_creation DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':utilisateur_id' => $utilisateur_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function listerPourSupport()
    {
        $sql = "SELECT r.*, u.nom AS utilisateur_nom 
                FROM reclamations r
                JOIN utilisateurs u ON r.utilisateur_id = u.id
                ORDER BY r.date_creation DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function statistiques()
    {
        $sql = "SELECT priorite, COUNT(*) AS total FROM reclamations GROUP BY priorite";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function statistiquesClient($clientId)
    {
        $sql = "SELECT priorite, COUNT(*) as total FROM reclamations WHERE utilisateur_id = :clientId GROUP BY priorite";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':clientId', $clientId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listerToutes()
    {
        $sql = "SELECT r.*, u.nom AS utilisateur_nom
            FROM reclamations r
            JOIN utilisateurs u ON r.utilisateur_id = u.id
            ORDER BY r.date_creation DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
