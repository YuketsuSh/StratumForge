<?php

require_once __DIR__ . '/../src/RequirementsChecker.php';

use StratumForge\RequirementsChecker;

$checker = new RequirementsChecker();
$results = $checker->run();

$allPassed = array_reduce($results, fn($carry, $item) => $carry && $item['status'] === 'success', true);
$hasErrors = array_reduce($results, fn($carry, $item) => $carry || $item['status'] === 'error', false);
$currentStep = 2;

include __DIR__ . '/layout.php';
?>

<div class="min-h-screen bg-gradient-to-br from-background to-muted p-4">
    <div class="max-w-4xl mx-auto">

        <!-- Progression -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2 text-sm text-muted-foreground">
                <?php foreach ($steps as $step): ?>
                    <div class="text-center w-full">
                        <div class="font-medium <?= $step['step'] === $currentStep ? 'text-primary' : '' ?>">
                            <?= $step['title'] ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="flex h-2 w-full bg-border rounded-full overflow-hidden">
                <div class="bg-primary transition-all duration-300" style="width: <?= ($currentStep - 1) / (count($steps) - 1) * 100 ?>%;"></div>
            </div>
        </div>

        <!-- Carte principale -->
        <div class="stratum-card p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold mb-2">Vérification du système</h1>
                <p class="text-muted-foreground">Validation des prérequis pour l'installation de Stratum CMS</p>
            </div>

            <!-- Résultats -->
            <div class="space-y-3 mb-6">
                <?php foreach ($results as $req): ?>
                    <div class="flex items-center gap-3">
                        <div class="status-check <?= $req['status'] === 'success' ? 'status-success' : ($req['status'] === 'error' ? 'status-error' : 'status-warning') ?> flex-1">
                            <div class="flex-shrink-0">
                                <?php if ($req['status'] === 'success'): ?>✅
                                <?php elseif ($req['status'] === 'error'): ?>❌
                                <?php else: ?>⚠️
                                <?php endif; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-medium text-sm"><?= htmlspecialchars($req['title']) ?></div>
                                <div class="text-xs text-muted-foreground"><?= htmlspecialchars($req['description']) ?></div>
                            </div>
                        </div>
                        <?php if ($req['status'] === 'error' && $req['fixUrl']): ?>
                            <a href="<?= htmlspecialchars($req['fixUrl']) ?>" target="_blank" rel="noopener noreferrer"
                               class="text-sm text-primary hover:underline flex items-center gap-1">
                                Aide 🔗
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Résumé -->
            <div class="mb-6 p-4 rounded-lg border">
                <?php if ($hasErrors): ?>
                    <div class="text-destructive">
                        <h3 class="font-semibold mb-1">Erreurs détectées</h3>
                        <p class="text-sm">Veuillez corriger les problèmes avant de continuer.</p>
                    </div>
                <?php else: ?>
                    <div class="text-success">
                        <h3 class="font-semibold mb-1">Système compatible</h3>
                        <p class="text-sm">Tous les prérequis sont satisfaits.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between">
                <a href="/" class="inline-flex items-center gap-2 px-4 py-2 text-sm border rounded-md hover:bg-muted transition">
                    ← Retour
                </a>
                <div class="flex gap-2">
                    <button onclick="window.location.reload(true)"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm border rounded-md hover:bg-muted transition">
                        🔄 Revérifier
                    </button>

                    <?php if (!$hasErrors): ?>
                        <a href="/install.php" class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-primary text-primary-foreground rounded-md hover:bg-primary/90 transition">
                            Continuer →
                        </a>
                    <?php else: ?>
                        <button class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-muted text-muted-foreground rounded-md cursor-not-allowed" disabled>
                            Continuer →
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
