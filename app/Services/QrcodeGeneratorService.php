<?php

namespace App\Services;

use App\Helpers\QRImageWithLogo;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Data\QRCodeDataException;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QRCodeOutputException;
use chillerlan\QRCode\Output\QRGdImageWEBP;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrcodeGeneratorService
{


    /**
     * @throws QRCodeDataException
     * @throws QRCodeOutputException
     * @throws \ErrorException
     */
    public function generate(?string $data, ?string $backgroundImagePath = null): mixed
    {
        if ($data === null) {return null;}



        if ($backgroundImagePath !== null && file_exists($backgroundImagePath)) {
            //$backgroundImage = imagecreatefromstring(file_get_contents($backgroundImagePath));

            $options = new QROptions;

            $options->version             = 5;
            $options->outputBase64        = false;
            $options->scale               = 6;
            $options->imageTransparent    = false;
            $options->drawCircularModules = true;
            $options->circleRadius        = 0.45;
            $options->keepAsSquare        = [
                QRMatrix::M_FINDER,
                QRMatrix::M_FINDER_DOT,
            ];
            $options->eccLevel            = EccLevel::H;
            $options->addLogoSpace        = true;
            $options->logoSpaceWidth      = 13;
            $options->logoSpaceHeight     = 13;

            $qrcode = new QRCode($options);
            $qrcode->addByteSegment($data);

            $qrOutputInterface = new QRImageWithLogo($options, $qrcode->getQRMatrix());
          //dd($qrOutputInterface->dump(null,$backgroundImagePath));
            return $qrOutputInterface->dump(null,$backgroundImagePath);
        }else{
            $options = new QROptions;

            $options->outputInterface     = QRGdImageWEBP::class;
            $options->quality             = 90;
            $options->scale               = 20;
            $options->bgColor             = [200, 150, 200];
            $options->imageTransparent    = true;
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
            return  (new QRCode($options))->render(data: $data);

        }


    }
}
