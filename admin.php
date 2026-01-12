<?php
// admin.php

// Fichier des invités
include 'includes/config.php';

$present = 0;
$total = 0;

// Vérification du fichier CSV
if (file_exists($csvFile) && ($handle = fopen($csvFile, 'r')) !== false) {
    while (($data = fgetcsv($handle)) !== false) {
        if (count($data) < 3) continue;
        $total++;
        if (trim(strtolower($data[3])) === 'présent') {
            $present++;
        }
    }
    fclose($handle);
}

$absents = max(0, $total - $present);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Administration - Statistiques de présence</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="js/chart.js"></script>
<link rel="stylesheet" href="includes/guestflow.css">
</head>
<body>

<header>GuestFlow - Administration</header>

<main>
    <canvas id="presenceChart"></canvas>

    <div id="stats">
        Présents : <?= $present ?> / <?= $total ?> invités
    </div>
</main>

<footer>cybermonde.org - version 0.1 <a href="index.php" title="Accueil" class="admin-link">🏠</a></footer>

<script>
const ctx = document.getElementById('presenceChart').getContext('2d');
new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Présents', 'Absents'],
        datasets: [{
            data: [<?= $present ?>, <?= $absents ?>],
            backgroundColor: ['#4caf50', '#f44336'],
            borderColor: '#ffffff',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { font: { size: 14 } }
            },
            title: {
                display: false
            }
        }
    }
});
</script>

</body>
</html>
