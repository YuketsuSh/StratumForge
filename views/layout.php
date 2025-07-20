<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Stratum Forge' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="/public/assets/css/app.css" rel="stylesheet" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
</head>
<body class="bg-background text-foreground font-inter min-h-screen antialiased">
<?php
$steps = [
    ['title' => 'Bienvenue', 'step' => 1],
    ['title' => 'Vérification', 'step' => 2],
    ['title' => 'Télécharger', 'step' => 3],
    ['title' => 'Terminé', 'step' => 4]
];
?>
<?= $content ?? '' ?>
</body>
</html>
