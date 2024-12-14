<?php
require_once 'connexion.php';
require_once 'classes/Reclamation.php';
session_start();

if (!isset($_SESSION['utilisateur'])) {
    header('Location: index.php');
    exit();
}

$reclamation = new Reclamation($pdo);


if ($_SESSION['utilisateur']['role'] === 'client') {

    $reclamations = $reclamation->listerPourClient($_SESSION['utilisateur']['id']);
    $statistiques = $reclamation->statistiquesClient($_SESSION['utilisateur']['id']);
} else if ($_SESSION['utilisateur']['role'] === 'support') {

    $reclamations = $reclamation->listerToutes();
    $statistiques = $reclamation->statistiques();
} else {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réclamations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="styles/afficher.css">
</head>

<body>
    <div class="container mt-5">
        <a href="logout.php" class="btn logout-btn">Déconnexion</a>

        <?php if ($_SESSION['utilisateur']['role'] === 'client') : ?>
            <a href="formulaire.php" class="btn btn-primary nouvelle-reclamation-btn">Nouvelle Réclamation</a>
        <?php endif; ?>

        <h1 class="text-center mb-4">Réclamations</h1>

        <div class="table-wrapper">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nom d'utilisateur</th>
                        <th>Description</th>
                        <th>Priorité</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reclamations as $rec) : ?>
                        <tr>
                            <?php if ($_SESSION['utilisateur']['role'] === 'support') : ?>
                                <td><?= htmlspecialchars($rec['utilisateur_nom']); ?></td>
                            <?php else : ?>
                                <td><?= htmlspecialchars($_SESSION['utilisateur']['nom']); ?></td>
                            <?php endif; ?>
                            <td><?= htmlspecialchars($rec['description']); ?></td>

                            <td class="<?php
                                        if ($rec['priorite'] === 'High') {
                                            echo 'high-priority';
                                        } elseif ($rec['priorite'] === 'Medium') {
                                            echo 'medium-priority';
                                        } else {
                                            echo 'low-priority';
                                        }
                                        ?>"><?= htmlspecialchars($rec['priorite']); ?></td>

                            <td class="<?php
                                        if ($rec['statut'] === 'Pending') {
                                            echo 'pending-status';
                                        } elseif ($rec['statut'] === 'Resolved') {
                                            echo 'resolved-status';
                                        } else {
                                            echo 'in-progress-status';
                                        }
                                        ?>"><?= htmlspecialchars($rec['statut']); ?></td>
                            <td><?= htmlspecialchars($rec['date_creation']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h2 class="mt-5">Statistiques des Réclamations</h2>
        <div class="chart-container">
            <canvas id="statistiquesChart"></canvas>
        </div>

        <script>
            const statistiquesData = <?php echo json_encode($statistiques); ?>;

            const labels = statistiquesData.map(item => item.priorite);
            const data = statistiquesData.map(item => item.total);

            const ctx = document.getElementById('statistiquesChart').getContext('2d');
            const statistiquesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Nombre de réclamations par priorité',
                        data: data,
                        backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                        borderColor: ['#218838', '#e0a800', '#c82333'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>