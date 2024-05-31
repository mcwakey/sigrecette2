<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class PackageVersionService
{
    protected string $packageJsonPath;

    public function __construct()
    {
        $this->packageJsonPath = base_path('package.json');
    }

    public function getVersion()
    {
        if (!File::exists($this->packageJsonPath)) {
            throw new \Exception('Le fichier package.json est introuvable.');
        }

        $packageJsonContent = File::get($this->packageJsonPath);

        $packageData = json_decode($packageJsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur lors du décodage du fichier JSON: ' . json_last_error_msg());
        }

        if (!isset($packageData['version'])) {
            throw new \Exception('La clé "version" est introuvable dans le fichier package.json.');
        }

        return $packageData['version'];
    }
}
