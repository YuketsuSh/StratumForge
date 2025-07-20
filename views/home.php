<?php
$title = "Bienvenue dans Stratum Forge";
ob_start();
?>

    <div class="min-h-screen bg-gradient-to-br from-background to-muted flex items-center justify-center p-4">
        <div class="w-full max-w-2xl">
            <div class="stratum-card p-8 text-center">

                <!-- Logo / Icone -->
                <div class="mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl stratum-gradient mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-primary-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v6H3V3zm0 8h18v10H3V11z" />
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold text-foreground mb-2">Bienvenue dans Stratum Forge</h1>
                    <p class="text-lg text-muted-foreground max-w-md mx-auto">
                        Cet outil va configurer et installer votre CMS Stratum automatiquement.
                    </p>
                </div>

                <!-- Feature blocks -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="p-4 rounded-lg bg-muted/50">
                        <div class="text-2xl mb-2">⚡</div>
                        <h3 class="font-semibold text-sm">Installation rapide</h3>
                        <p class="text-xs text-muted-foreground">Configuration automatisée</p>
                    </div>
                    <div class="p-4 rounded-lg bg-muted/50">
                        <div class="text-2xl mb-2">🔧</div>
                        <h3 class="font-semibold text-sm">Vérifications système</h3>
                        <p class="text-xs text-muted-foreground">Pré-requis validés</p>
                    </div>
                    <div class="p-4 rounded-lg bg-muted/50">
                        <div class="text-2xl mb-2">📦</div>
                        <h3 class="font-semibold text-sm">CMS modulaire</h3>
                        <p class="text-xs text-muted-foreground">Architecture en couches</p>
                    </div>
                </div>

                <!-- Call to action -->
                <div class="space-y-4">
                    <a href="?step=check" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary text-primary-foreground rounded-md text-sm font-medium hover:bg-primary/90 transition">
                        Commencer l'installation
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                    <p class="text-xs text-muted-foreground">
                        L'installation prend généralement 2 à 5 minutes selon votre système.
                    </p>
                </div>
            </div>
        </div>
    </div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
