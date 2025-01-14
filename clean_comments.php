<?php

$directory = __DIR__ . '/app'; // Chemin vers le dossier à nettoyer
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());

        // Supprimer les blocs de commentaires contenant du code
        $cleanedContent = preg_replace_callback(
            '/^\s*\/\/.*(\n\s*\/\/.*)*$/m',
            function ($matches) {
                // Garder les commentaires sans code (simple texte) si nécessaire
                $block = $matches[0];
                if (preg_match('/[{}();$]/', $block)) {
                    return ''; // Supprimer si le bloc contient du code
                }
                return $block; // Conserver les autres commentaires
            },
            $content
        );

        // Supprimer les lignes vides résultantes de la suppression
        $cleanedContent = preg_replace('/^\s*\n/m', '', $cleanedContent);

        if ($content !== $cleanedContent) {
            file_put_contents($file->getPathname(), $cleanedContent);
            echo "Nettoyé : " . $file->getPathname() . PHP_EOL;
        }
    }
}
