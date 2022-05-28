<?php

namespace ModStart\Core\Util;


class QrcodeUtil
{
    
    public static function png($content, $size = 200)
    {
        $renderer = new \BaconQrCode\Renderer\Image\Png();
        $renderer->setMargin(0);
        $renderer->setHeight($size);
        $renderer->setWidth($size);
        $writer = new \BaconQrCode\Writer($renderer);
        return $writer->writeString($content);
    }

    
    public static function pngBase64String($content, $size = 200)
    {
        return 'data:image/png;base64,' . base64_encode(self::png($content, $size));
    }
}
