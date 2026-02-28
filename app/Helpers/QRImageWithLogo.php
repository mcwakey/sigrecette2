<?php

namespace App\Helpers;

use chillerlan\QRCode\Output\QRCodeOutputException;
use chillerlan\QRCode\Output\QRGdImagePNG;
use ErrorException;
use GdImage;

class QRImageWithLogo extends QRGdImagePNG
{
    function convertToPng(string $logoPath): ?string
    {
        $imageInfo = getimagesize($logoPath);

        if (!$imageInfo) {
            return null;  // Fichier non valide
        }

        $mime = $imageInfo['mime'];
        $im = null;

        switch ($mime) {
            case 'image/png':
                return $logoPath;  // Déjà PNG, on retourne directement
            case 'image/jpeg':
                $im = imagecreatefromjpeg($logoPath);
                break;
            case 'image/gif':
                $im = imagecreatefromgif($logoPath);
                break;
            case 'image/webp':
                $im = imagecreatefromwebp($logoPath);
                break;
            default:
                return null;  // Format non supporté
        }

        if (!$im) {
            return null;
        }

        return $im;
    }

    function convertToPngN(string $logoPath): ?GdImage
    {
        // Obtenir les informations du fichier
        $imageInfo = getimagesize($logoPath);

        if (!$imageInfo) {
            return null;  // Image invalide
        }

        $mime = $imageInfo['mime'];
        $im = null;

        // Créer une ressource image en fonction du format
        switch ($mime) {
            case 'image/png':
                $im = imagecreatefrompng($logoPath);
                break;
            case 'image/jpeg':
                $im = imagecreatefromjpeg($logoPath);
                break;
            case 'image/gif':
                $im = imagecreatefromgif($logoPath);
                break;
            case 'image/webp':
                $im = imagecreatefromwebp($logoPath);
                break;
            default:
                return null;  // Format non supporté
        }

        // Si l'image est déjà en PNG, on la retourne
        if ($mime === 'image/png') {
            return $im;
        }

        // Sinon, convertir en PNG
        $output = imagecreatetruecolor(imagesx($im), imagesy($im));

        // Gérer la transparence
        imagealphablending($output, false);
        imagesavealpha($output, true);
        $transparent = imagecolorallocatealpha($output, 0, 0, 0, 127);
        imagefilledrectangle($output, 0, 0, imagesx($im), imagesy($im), $transparent);

        // Copier l'image source dans la nouvelle image PNG
        imagecopy($output, $im, 0, 0, 0, 0, imagesx($im), imagesy($im));

        // Nettoyer l'ancienne image
        imagedestroy($im);

        return $output;
    }

    /**
     * @throws QRCodeOutputException|ErrorException
     */
    public function dump(string|null $file = null, string|null $logo = null): string
    {
        $logo ??= '';

        $this->options->returnResource = true;
        if (!is_file($logo) || !is_readable($logo)) {
            throw new QRCodeOutputException('invalid logo');
        }

        parent::dump($file);

        $im = $this->convertToPngN($logo);


        if ($im === false) {
            throw new QRCodeOutputException('imagecreatefrompng() error');
        }

        $w = imagesx($im);
        $h = imagesy($im);

        $lw = (($this->options->logoSpaceWidth - 2) * $this->options->scale);
        $lh = (($this->options->logoSpaceHeight - 2) * $this->options->scale);

        $ql = ($this->matrix->getSize() * $this->options->scale);

        imagecopyresampled($this->image, $im, (($ql - $lw) / 2), (($ql - $lh) / 2), 0, 0, $lw, $lh, $w, $h);

        $imageData = $this->dumpImage();

        $this->saveToFile($imageData, $file);

        return $this->toBase64DataURI($imageData);
    }
}
