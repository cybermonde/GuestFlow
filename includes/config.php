<?php
/**
 * GuestFlow – configuration du fichier reception.csv
 * Sélection du bon chemin selon l’environnement serveur
 */

// IP du serveur (ou du client en LAN)
$serverIp   = $_SERVER['SERVER_ADDR'] ?? '';
$serverName = $_SERVER['SERVER_NAME'] ?? '';

// Cas 1 : réseau local 192.168.*
if (preg_match('/^192\.168\./', $serverIp)) {
    $csvFile = '/var/www/data/reception.csv';

// Cas 2 : serveur public
} elseif ($serverName === 'guestflow.domaine.tld') {
    $csvFile = '../data/reception.csv';

// Sécurité : cas non reconnu
} else {
    die('Environnement serveur non reconnu. Impossible de déterminer reception.csv');
}

// Vérification de l'existence et des droits du fichier CSV
if (!file_exists($csvFile)) {
    die("Erreur : Le fichier $csvFile n'existe pas à l'emplacement spécifié.");
}

if (!is_writable($csvFile)) {
    die("Erreur : Le fichier $csvFile existe mais n'est pas accessible en écriture.");
}
