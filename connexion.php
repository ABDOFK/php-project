<?php
$host = "localhost";
$dbname = "gestion_reclamations";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

require_once 'classes/User.php';
require_once 'classes/Reclamation.php';

$user = new User($pdo);
$reclamation = new Reclamation($pdo);
