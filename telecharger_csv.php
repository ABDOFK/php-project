<?php
require_once 'connexion.php';
require_once 'classes/Reclamation.php';

session_start();

// Vérifier si l'utilisateur est connecté et a le rôle "support"
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'support') {
    header('Location: index.php');
    exit();
}

// Initialisation de l'objet Reclamation
$reclamation = new Reclamation($pdo);
$reclamations = $reclamation->listerToutes(); // Récupérer toutes les réclamations pour le support

// Définir les en-têtes pour indiquer qu'il s'agit d'un fichier CSV
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="reclamations.csv"');

// Ouvrir un flux pour écrire les données
$output = fopen('php://output', 'w');

// Ajouter un BOM UTF-8 pour que les fichiers soient bien reconnus
fwrite($output, "\xEF\xBB\xBF");

// Définir les en-têtes du CSV
$headers = ['ID', 'Nom d\'Utilisateur', 'Description', 'Priorité', 'Statut', 'Date de Création'];

// Écrire les en-têtes
fputcsv($output, $headers, ',', '"');

// Ajouter les données des réclamations ligne par ligne
foreach ($reclamations as $rec) {
    // Créer un tableau de données en sanitisant chaque colonne
    $data = [
        $rec['id'],
        // Sanitize each field to prevent CSV injection and handle special characters
        $rec['utilisateur_nom'] !== null ? $this->sanitizeCSVField($rec['utilisateur_nom']) : '',
        $rec['description'] !== null ? $this->sanitizeCSVField($rec['description']) : '',
        $rec['priorite'] !== null ? $this->sanitizeCSVField($rec['priorite']) : '',
        $rec['statut'] !== null ? $this->sanitizeCSVField($rec['statut']) : '',
        $rec['date_creation'] !== null ? $this->sanitizeCSVField($rec['date_creation']) : ''
    ];

    // Écrire la ligne de données
    fputcsv($output, $data, ',', '"');
}

// Fermer le flux
fclose($output);
exit();

// Méthode de sanitisation pour les champs CSV
function sanitizeCSVField($field) {
    // Convertir en chaîne
    $field = (string)$field;
    
    // Échapper les caractères spéciaux pour CSV
    // - Remplacer les guillemets par des guillemets doubles
    // - Échapper les sauts de ligne et les retours chariot
    $field = str_replace(
        ['"', "\r\n", "\r", "\n"], 
        ['""', ' ', ' ', ' '], 
        $field
    );
    
    // Si le champ contient des virgules, des guillemets ou des sauts de ligne, 
    // l'entourer de guillemets
    if (strpos($field, ',') !== false || 
        strpos($field, '"') !== false || 
        strpos($field, "\n") !== false) {
        $field = '"' . $field . '"';
    }
    
    return $field;
}
?>