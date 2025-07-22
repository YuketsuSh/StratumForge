<?php

require_once __DIR__ . '/../../src/Downloader.php';

use StratumForge\Downloader;

header('Content-Type: text/plain');

$step = $_GET['step'] ?? null;

try {
    $downloader = new Downloader();

    switch ($step) {
        case 'download':
            $downloader->download();
            echo "✓ CMS téléchargé et prêt.";
            break;

        case 'prepare':
            $downloader->prepare();
            echo "✓ Dépendances installées et projet prêt.";
            break;

        case 'finalize':
            $downloader->finalize();
            echo "✓ Le CMS a remplacé le bootstrapper.";
            break;

        case 'permissions':
            $downloader->setPermissions();
            break;

        case 'cleanup':
            echo "✓ Fichiers temporaires supprimés.";
            break;

        default:
            http_response_code(400);
            echo "❌ Étape inconnue.";
    }
} catch (Exception $e) {
    http_response_code(500);
    echo "❌ Erreur : " . $e->getMessage();
}
