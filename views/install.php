<?php

$title = "Téléchargement";
$currentStep = 3;

ob_start();
?>

    <div class="min-h-screen bg-gradient-to-br from-background to-muted p-4">
        <div class="max-w-4xl mx-auto">

            <!-- Progression -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2 text-sm text-muted-foreground">
                    <?php
                    $stepsUI = ['Bienvenue', 'Vérification', 'Télécharger', 'Terminé'];
                    foreach ($stepsUI as $index => $step):
                        ?>
                        <div class="text-center w-full">
                            <div class="font-medium <?= ($index + 1) === $currentStep ? 'text-primary' : (($index + 1) < $currentStep ? 'text-success' : '') ?>">
                                <?= $step ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="flex h-2 w-full bg-border rounded-full overflow-hidden">
                    <div class="bg-primary transition-all duration-300" style="width: <?= (($currentStep - 1) / (count($stepsUI) - 1)) * 100 ?>%;"></div>
                </div>
            </div>

            <!-- Carte d'installation -->
            <div class="stratum-card p-6">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold mb-2">Installation en cours</h1>
                    <p class="text-muted-foreground">Patientez pendant que Stratum Forge configure votre CMS...</p>
                </div>

                <!-- Terminal -->
                <div class="stratum-terminal mb-6">
                    <div class="flex items-center gap-2 mb-3 pb-2 border-b border-border">
                        <div class="flex gap-1">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        </div>
                        <span class="text-sm font-medium text-muted-foreground">Stratum Forge Console</span>
                        <div class="ml-auto">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        </div>
                    </div>

                    <div class="h-64 overflow-y-auto font-mono text-sm space-y-1" id="terminal">
                        <div class="text-muted-foreground">Initialisation...</div>
                    </div>
                </div>

                <!-- Message final -->
                <div id="completeMessage" class="hidden text-center p-4 rounded-lg bg-success/10 border border-success/20">
                    <div class="text-success font-semibold mb-2">🎉 Installation terminée avec succès !</div>
                    <p class="text-sm text-muted-foreground">Redirection automatique en cours...</p>
                </div>
            </div>

        </div>
    </div>

    <script>
        const baseURL = "/index.php?api=install-step";

        const steps = [
            { label: "Téléchargement de Stratum CMS...", step: "download" },
            { label: "Préparation de l'environnement...", step: "prepare" },
            { label: "Finalisation de l'installation...", step: "finalize" },
            { label: "Configuration des permissions...", step: "permissions" },
            { label: "Nettoyage des fichiers temporaires...", step: "cleanup" },
        ];

        const terminal = document.getElementById("terminal");
        const complete = document.getElementById("completeMessage");

        async function appendLog(message) {
            const line = document.createElement("div");
            line.className = "flex items-start gap-2";
            line.innerHTML = `<span class="text-primary select-none">$</span><span class="text-foreground">${message}</span>`;
            terminal.appendChild(line);
            terminal.scrollTop = terminal.scrollHeight;
        }

        async function runSteps() {
            terminal.innerHTML = "";
            let failed = false;

            for (const step of steps) {
                await appendLog(step.label);
                try {
                    const res = await fetch(`${baseURL}&step=${step.step}`);
                    const txt = await res.text();

                    if (!res.ok || txt.includes("❌")) {
                        await appendLog(txt || "Erreur inconnue.");
                        failed = true;
                        break;
                    }

                    if (txt.trim()) await appendLog(txt.trim());
                } catch (e) {
                    await appendLog("❌ Erreur JS : " + e.message);
                    failed = true;
                    break;
                }
                await new Promise(r => setTimeout(r, 1000));
            }

            complete.classList.remove("hidden");
            if (failed) {
                complete.classList.remove("bg-success/10", "border-success/20");
                complete.classList.add("bg-destructive/10", "border-destructive/20");
                complete.innerHTML = `
            <div class="text-destructive font-semibold mb-2">❌ Installation échouée</div>
            <p class="text-sm text-muted-foreground">Consultez les logs ci-dessus.</p>
        `;
            } else {
                setTimeout(() => {
                    window.location.href = "/install";
                }, 3000);
            }
        }

        runSteps();
    </script>


<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
