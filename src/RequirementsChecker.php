<?php

namespace StratumForge;

class RequirementsChecker
{

    protected array $results = [];

    public function run(): array {
        $this->checkPHP();
        $this->checkExt();
        $this->checkComposer();
        $this->checkNode();
        $this->checkNpm();
        $this->checkPermissions();
        return $this->results;
    }

    private function addResult(string $id, string $title, string $description, bool $status, ?string $fixUrl = null): void
    {
        $this->results[] = [
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'status' => $status ? 'success' : 'error',
            'fixUrl' => $status ? null : $fixUrl,
        ];
    }

    private function checkPHP(): void
    {
        $versionOk = version_compare(PHP_VERSION, '8.1', '>=');
        $this->addResult(
            'php',
            'PHP >= 8.1',
            'Version PHP installée : ' . PHP_VERSION,
            $versionOk,
            'https://www.php.net/manual/fr/install.php'
        );
    }



    private function checkExt(): void {
        $required = [
            'curl', 'json', 'mbstring', 'openssl', 'pdo', 'pdo_mysql',
            'ctype', 'tokenizer', 'fileinfo', 'dom', 'zip', 'bcmath', 'gd'
        ];

        $missing = array_filter($required, fn($ext) => !extension_loaded($ext));

        $this->addResult(
            'extensions',
            'Extensions PHP',
            'curl, json, mbstring, openssl, pdo, tokenizer, fileinfo, etc.',
            empty($missing),
            'https://php.net/manual/fr/extensions.install.php'
        );
    }

    private function checkComposer(): void
    {
        exec('composer --version 2>&1', $output, $code);
        $this->addResult(
            'composer',
            'Composer',
            'Gestionnaire de dépendances PHP',
            $code === 0,
            'https://getcomposer.org/download/'
        );
    }

    private function checkNode(): void {
        exec('node -v 2>&1', $output, $code);
        $version = $output[0] ?? 'non détectée';
        $isValid = false;

        if (preg_match('/v(\d+)\./', $version, $matches)) {
            $isValid = (int)$matches[1] >= 16;
        }

        $this->addResult(
            'node',
            'Node.js >= 16',
            'Version détectée : ' . $version,
            $isValid,
            'https://nodejs.org/en/download/'
        );
    }

    private function checkNpm(): void
    {
        exec('npm -v 2>&1', $output, $code);
        $this->addResult(
            'npm',
            'NPM',
            'Gestionnaire de paquets Node.js',
            $code === 0,
            'https://docs.npmjs.com/downloading-and-installing-node-js-and-npm'
        );
    }

    private function checkPermissions(): void
    {
        $writable = is_writable(__DIR__) && is_writable(sys_get_temp_dir());
        $this->addResult(
            'permissions',
            'Permissions système',
            'Accès en écriture requis dans le répertoire temporaire et de travail.',
            $writable,
            null
        );
    }

    protected function getInstallCMD(string $type, array $extras = []){
        $os = strtolower(PHP_OS_FAMILY);
        $base = match ($type) {
            'php' => [
                'Linux' => 'sudo apt install php8.1',
                'Darwin' => 'brew install php',
                'Windows' => 'https://windows.php.net/download'
            ],
            'php-extensions' => [
                'Linux' => 'sudo apt install php8.1-' . implode(' php8.1-', $extras),
                'Darwin' => 'brew install php',
                'Windows' => 'https://www.php.net/manual/en/install.windows.extensions.php'
            ],
            default => ['Linux' => '# inconnu', 'Darwin' => '# inconnu', 'Windows' => '# inconnu']
        };

        return $base[$os] ?? 'https://stratumcms.com/documentation';
    }

}