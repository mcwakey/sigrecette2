<?php

namespace App\Services;

use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QRGdImageWEBP;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrcodeGeneratorService
{


    public function generate(?string $data,?string $backgroundImagePath = null): mixed
    {
        if ($data === null) {return null;}
        $options = new QROptions;

// $outputInterface can be one of the classes listed in `QROutputInterface::MODES`
        $options->outputInterface     = QRGdImageWEBP::class;
        $options->quality             = 90;
// the size of one qr module in pixels
        $options->scale               = 20;
        $options->bgColor             = [200, 150, 200];
        $options->imageTransparent    = true;
// the color that will be set transparent
// @see https://www.php.net/manual/en/function.imagecolortransparent
        $options->transparencyColor   = [200, 150, 200];
        $options->drawCircularModules = true;
        $options->drawLightModules    = true;
        $options->circleRadius        = 0.4;
        $options->keepAsSquare        = [
            QRMatrix::M_FINDER_DARK,
            QRMatrix::M_FINDER_DOT,
            QRMatrix::M_ALIGNMENT_DARK,
        ];
        $options->moduleValues        = [
            QRMatrix::M_FINDER_DARK    => [0, 63, 255], // dark (true)
            QRMatrix::M_FINDER_DOT     => [0, 63, 255], // finder dot, dark (true)
            QRMatrix::M_FINDER         => [233, 233, 233], // light (false)
            QRMatrix::M_ALIGNMENT_DARK => [255, 0, 255],
            QRMatrix::M_ALIGNMENT      => [233, 233, 233],
            QRMatrix::M_DATA_DARK      => [0, 0, 0],
            QRMatrix::M_DATA           => [233, 233, 233],
        ];
        $qrCodeImage = (new QRCode($options))->render(data: $data);

        if ($backgroundImagePath !== null && file_exists($backgroundImagePath)) {
            $backgroundImage = imagecreatefromstring(file_get_contents($backgroundImagePath));

            imagecopymerge($backgroundImage, $qrCodeImage, 0, 0, 0, 0, imagesx($qrCodeImage), imagesy($qrCodeImage), 100);
            return $backgroundImage;
        }

        return $qrCodeImage;
    }
}
