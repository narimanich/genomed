<?php

namespace app\components;

use Endroid\QrCode\Builder\Builder;

class QrCodeGenerator
{
    public function generate(string $url):string
    {
        $qrCode = Builder::create()
            ->data($url)
            ->size(300)
            ->build();

        return $qrCode->getString();
    }
}