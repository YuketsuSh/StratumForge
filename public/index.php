<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';


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
