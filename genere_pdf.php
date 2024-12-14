<?php
require_once 'connexion.php';
require_once 'classes/Reclamation.php';
require_once 'fpdf186/fpdf.php'; // Inclure la bibliothèque FPDF

session_start();

// Vérifier si l'utilisateur est connecté et s'il est support
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'support') {
    header('Location: index.php');
    exit();
}

// Initialisation de l'objet Reclamation
$reclamation = new Reclamation($pdo);
$reclamations = $reclamation->listerToutes(); // Récupérer toutes les réclamations pour le support

// Création du PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);

// Titre du document
$pdf->Cell(0, 10, 'Liste des Reclamations', 0, 1, 'C');
$pdf->Ln(10);

// En-tête du tableau
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(20, 10, 'ID', 1);
$pdf->Cell(40, 10, 'Utilisateur', 1);
$pdf->Cell(60, 10, 'Description', 1);
$pdf->Cell(30, 10, 'Priorite', 1);
$pdf->Cell(30, 10, 'Statut', 1);
$pdf->Ln();

// Contenu du tableau
$pdf->SetFont('Arial', '', 12);
foreach ($reclamations as $rec) {
    $pdf->Cell(20, 10, $rec['id'], 1);
    $pdf->Cell(40, 10, utf8_decode($rec['utilisateur_nom']), 1);
    $pdf->Cell(60, 10, utf8_decode(substr($rec['description'], 0, 30) . '...'), 1);
    $pdf->Cell(30, 10, utf8_decode($rec['priorite']), 1);
    $pdf->Cell(30, 10, utf8_decode($rec['statut']), 1);
    $pdf->Ln();
}

// Sortie du fichier PDF
$pdf->Output('D', 'reclamations.pdf'); // Téléchargement automatique
?>
