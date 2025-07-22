<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$uri = $_SERVER['REQUEST_URI'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);

if (str_starts_with($uri, $scriptName)) {
    $path = substr($uri, strlen($scriptName));
} else {
    $path = $uri;
}
$path = parse_url($path, PHP_URL_PATH);

if (isset($_GET['api']) && $_GET['api'] === 'install-step') {
    require __DIR__ . '/api/install-step.php';
    exit;
}

$step = $_GET['step'] ?? 'home';

switch ($step) {
    case 'check':
        require __DIR__ . '/../views/check.php';
        break;
    case 'install':
        require __DIR__ . '/../views/install.php';
        break;
    case 'done':
        require __DIR__ . '/../views/done.php';
        break;
    case 'home':
    default:
        require __DIR__ . '/../views/home.php';
        break;
}
