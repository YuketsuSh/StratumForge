<?php

namespace StratumForge;

class Downloader
{
    private const GITHUB_API_RELEASE = 'https://api.github.com/repos/YuketsuSh/Stratum/releases/latest';
    private const CACHE_FILE = __DIR__ . '/../.install-cache';

    private string $tmpDir;
    private string $projectRoot;
    private string $extractedCmsPath;

    public function __construct()
    {
        $this->tmpDir = sys_get_temp_dir() . '/stratum_download';
        $this->projectRoot = realpath(__DIR__ . '/../');

        if (file_exists(self::CACHE_FILE)) {
            $this->extractedCmsPath = trim(file_get_contents(self::CACHE_FILE));
        }
    }

    public function download(): void
    {
        $this->prepareTmp();

        $zipUrl = $this->fetchLatestReleaseZip();
        $zipPath = $this->tmpDir . '/stratum.zip';

        $zipContent = @file_get_contents($zipUrl, false, stream_context_create([
            "http" => ["user_agent" => "StratumForge Bootstrapper"]
        ]));

        if ($zipContent === false) {
            throw new \RuntimeException("Échec du téléchargement de l'archive depuis GitHub.");
        }

        file_put_contents($zipPath, $zipContent);

        $zip = new \ZipArchive();
        if ($zip->open($zipPath) !== true) {
            throw new \RuntimeException("Impossible d'ouvrir l'archive ZIP.");
        }

        $extractPath = $this->tmpDir . '/stratum';
        mkdir($extractPath, 0755, true);
        $zip->extractTo($extractPath);
        $zip->close();

        $this->extractedCmsPath = $this->detectCmsRoot($extractPath);

        file_put_contents(self::CACHE_FILE, $this->extractedCmsPath);
    }

    public function prepare(): void
    {
        $this->assertCmsPathLoaded();

        chdir($this->extractedCmsPath);

        exec("composer install --no-interaction");
        exec("npm install");
        exec("npm run dev:install && npm run dev:admin && npm run dev:default");

        if (!file_exists('.env') && file_exists('.env.example')) {
            copy('.env.example', '.env');
        }

        exec("php artisan key:generate --ansi");
        exec("php artisan storage:link");
    }

    public function finalize(): void
    {
        $this->assertCmsPathLoaded();

        $this->deleteDirIfExists($this->projectRoot . '/views');
        $this->deleteDirIfExists($this->projectRoot . '/src');
        $this->deleteDirIfExists($this->projectRoot . '/tmp');
        $this->deleteDirIfExists($this->projectRoot . '/vendor');
        $this->deleteDirIfExists($this->projectRoot . '/public');

        $this->copyDir($this->extractedCmsPath, $this->projectRoot);
    }

    public function setPermissions(): void
    {
        if (stripos(PHP_OS, 'WIN') === 0) {
            echo "ℹ️ Permissions ignorées sous Windows (non applicable).";
            return;
        }

        exec('chmod -R 775 storage bootstrap/cache');
        exec('chown -R www-data:www-data .');
        echo "✓ Permissions définies pour Linux.";
    }

    public function cleanUp(): void
    {
        $this->deleteDir($this->tmpDir);
        if (file_exists(self::CACHE_FILE)) {
            unlink(self::CACHE_FILE);
        }
    }

    private function prepareTmp(): void
    {
        if (is_dir($this->tmpDir)) {
            $this->deleteDir($this->tmpDir);
        }
        mkdir($this->tmpDir, 0755, true);
    }

    private function fetchLatestReleaseZip(): string
    {
        $response = file_get_contents(self::GITHUB_API_RELEASE, false, stream_context_create([
            "http" => ["user_agent" => "StratumForge Bootstrapper"]
        ]));

        $data = json_decode($response, true);
        if (!isset($data['assets'][0]['browser_download_url'])) {
            throw new \RuntimeException("Aucune archive ZIP trouvée dans la dernière release.");
        }

        return $data['assets'][0]['browser_download_url'];
    }

    private function detectCmsRoot(string $base): string
    {
        $entries = array_diff(scandir($base), ['.', '..']);
        if (count($entries) === 1) {
            return $base . '/' . reset($entries);
        }
        return $base;
    }

    private function assertCmsPathLoaded(): void
    {
        if (empty($this->extractedCmsPath) || !is_dir($this->extractedCmsPath)) {
            throw new \RuntimeException("Chemin du CMS non initialisé. Étape 'download' manquante ou échouée.");
        }
    }

    private function deleteDirIfExists(string $dir): void
    {
        if (is_dir($dir)) {
            $this->deleteDir($dir);
        }
    }

    private function deleteDir(string $dir): void
    {
        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') continue;
            $path = $dir . '/' . $item;
            is_dir($path) ? $this->deleteDir($path) : unlink($path);
        }
        rmdir($dir);
    }

    private function copyDir(string $src, string $dst): void
    {
        @mkdir($dst, 0755, true);
        foreach (scandir($src) as $file) {
            if ($file === '.' || $file === '..') continue;
            $srcPath = "$src/$file";
            $dstPath = "$dst/$file";

            if (is_dir($srcPath)) {
                $this->copyDir($srcPath, $dstPath);
            } else {
                copy($srcPath, $dstPath);
            }
        }
    }
}
