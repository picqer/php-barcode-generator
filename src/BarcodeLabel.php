<?php

namespace Picqer\Barcode;


use Picqer\Barcode\Exceptions\BarcodeException;

class BarcodeLabel
{
    public $text;
    public $textAlign;
    public $font;
    public $fontSize;
    public $color = [0, 0, 0];
    public $marginTop;
    public $marginBottom;
    public $marginLeft;
    public $marginRight;

    /**@see https://ux.stackexchange.com/questions/34120/relationship-between-font-size-and-width-of-container */
    const FONT_RATIO = 1.56261;

    /**
     * BarcodeLabel constructor.
     * @param $text
     * @param $textAlign
     * @param $fontSize
     * @param $color
     * @param $marginTop
     * @param $marginBottom
     * @param $marginLeft
     * @param $marginRight
     */
    public function __construct(
        $text,
        $textAlign = 'center',
        $fontSize = 12,
        $color = [0, 0, 0],
        $marginTop = 0,
        $marginBottom = 0,
        $marginLeft = 0,
        $marginRight = 0
    ) {
        $this->text = $text;
        $this->textAlign = $textAlign;
        $this->font = __DIR__ . '/fonts/arial.ttf';
        $this->fontSize = $fontSize;
        $this->marginTop = $marginTop;
        $this->marginBottom = $marginBottom;
        $this->marginLeft = $marginLeft;
        $this->marginRight = $marginRight;
    }

    /**
     * Set font for label
     * @param $fontPath
     */
    public function setFont($fontPath)
    {
        if (file_exists($fontPath)) {
            $this->font = $fontPath;
        }
    }

    /**
     * Add label for barcode
     * @param $imageType
     * @param $image
     * @return string
     * @throws BarcodeException
     */
    public function withLabel($imageType, $image)
    {
        if ($imageType == 'PNG') {
            return $this->labelPng($image);
        }
        if ($imageType == 'JPG') {
            return $this->labelJpg($image);
        }
        if ($imageType == 'SVG') {
            return $this->labelSvg($image);
        }
        if ($imageType == 'HTML') {
            return $this->labelHtml($image);
        }

        return $image;
    }


    /**
     * HTML format label
     * @param $barcodeData
     * @return mixed
     */
    private function labelHtml($barcodeData)
    {
        $widthFactor = $barcodeData['widthFactor'];
        $totalHeight = $barcodeData['totalHeight'];
        $color = $barcodeData['color'];
        $width = ($barcodeData['maxWidth'] * $widthFactor);

        $heightWithLabel = $totalHeight + $this->fontSize + $this->marginBottom + $this->marginTop;
        $labelWidth = ($this->fontSize * strlen($this->text) / self::FONT_RATIO) + $this->marginLeft + $this->marginRight;
        $widthWithLabel = max($width, $labelWidth);

        $x = $this->marginLeft;
        $y = $totalHeight;
        if ($this->textAlign == 'center') {
            $x = ($widthWithLabel - $labelWidth) / 2;
        }

        $html = '<div style="font-size:0;position:relative;width:' . $widthWithLabel . 'px;height:' . ($heightWithLabel) . 'px;">' . "\n";

        $positionHorizontal = 0;
        foreach ($barcodeData['bars'] as $bar) {
            $barWidth = round(($bar['width'] * $widthFactor), 3);
            $barHeight = round(($bar['height'] * $totalHeight / $barcodeData['maxHeight']), 3);

            if ($bar['drawBar']) {
                $positionVertical = round(($bar['positionVertical'] * $totalHeight / $barcodeData['maxHeight']), 3);
                // draw a vertical bar
                $html .= '<div style="background-color:' . $color . ';width:' . $barWidth . 'px;height:' . $barHeight . 'px;position:absolute;left:' . $positionHorizontal . 'px;top:' . $positionVertical . 'px;">&nbsp;</div>' . "\n";
            }

            $positionHorizontal += $barWidth;
        }

        // add label text
        $html .= '<p style="margin:0;padding:0; font-size:'.$this->fontSize.'px; color:' . $color . ';position:absolute;left:' . $x . 'px;top:' . $y . 'px;">'.$this->text.'</p>' . "\n";

        $html .= '</div>' . "\n";
        return $html;

    }

    /**
     * SVG format label
     * @param $barcodeData
     * @return mixed
     */
    private function labelSvg($barcodeData)
    {

        $widthFactor = $barcodeData['widthFactor'];
        $totalHeight = $barcodeData['totalHeight'];
        $color = $barcodeData['color'];

        // replace table for special characters
        $repstr = array("\0" => '', '&' => '&amp;', '<' => '&lt;', '>' => '&gt;');

        $width = round(($barcodeData['maxWidth'] * $widthFactor), 3);


        $heightWithLabel = $totalHeight + $this->fontSize + $this->marginBottom + $this->marginTop;
        $labelWidth = ($this->fontSize * strlen($this->text) / self::FONT_RATIO) + $this->marginLeft + $this->marginRight;
        $widthWithLabel = max($width, $labelWidth);

        // use font file
        $x = $this->marginLeft;
        $y = $totalHeight + $this->fontSize + $this->marginTop;
        if ($this->textAlign == 'center') {
            $x = ($widthWithLabel - $labelWidth) / 2;
        }

        $svg = '<?xml version="1.0" standalone="no" ?>' . "\n";
        $svg .= '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">' . "\n";
        $svg .= '<svg width="' . $widthWithLabel . '" height="' . $heightWithLabel . '" viewBox="0 0 ' . $widthWithLabel . ' ' . $heightWithLabel . '" version="1.1" xmlns="http://www.w3.org/2000/svg">' . "\n";
        $svg .= "\t" . '<desc>' . strtr($barcodeData['code'], $repstr) . '</desc>' . "\n";
        $svg .= "\t" . '<g id="bars" fill="' . $color . '" stroke="none">' . "\n";
        // print bars
        $positionHorizontal = 0;
        foreach ($barcodeData['bars'] as $bar) {
            $barWidth = round(($bar['width'] * $widthFactor), 3);
            $barHeight = round(($bar['height'] * $totalHeight / $barcodeData['maxHeight']), 3);
            if ($bar['drawBar']) {
                $positionVertical = round(($bar['positionVertical'] * $totalHeight / $barcodeData['maxHeight']), 3);
                // draw a vertical bar
                $svg .= "\t\t" . '<rect x="' . $positionHorizontal . '" y="' . $positionVertical . '" width="' . $barWidth . '" height="' . $barHeight . '" />' . "\n";
            }
            $positionHorizontal += $barWidth;
        }
        // add label text
        $svg .= "\t\t" . '<text fill="black" x="' . $x . '" y="' . $y . '" style="font-size:' . $this->fontSize . 'px; font-family:Arial" >' . "{$this->text}</text>\n";
        $svg .= "\t" . '</g>' . "\n";
        $svg .= '</svg>' . "\n";
        return $svg;

    }


    /**
     * JPG format label
     * @param $image
     * @return string
     * @throws BarcodeException
     */
    private function labelJpg($image)
    {

        if (function_exists('imagecreate')) {
            // get origin image info
            $originImage = imagecreatefromstring($image);
            $width = imagesx($originImage);
            $height = imagesy($originImage);
            // cal new image width and height
            $heightWithLabel = $height + $this->fontSize + $this->marginBottom + $this->marginTop;
            $labelWidth = ($this->fontSize * strlen($this->text) / self::FONT_RATIO) + $this->marginLeft + $this->marginRight;
            $widthWithLabel = max($width, $labelWidth);
            // create a new image
            $labelImage = imagecreate($widthWithLabel, $heightWithLabel);
            $colorBackground = imagecolorallocate($labelImage, 255, 255, 255);
            imagecolortransparent($labelImage, $colorBackground);
            // black font color
            $black = imagecolorallocate($labelImage, 0, 0, 0);
            // use font file
            $x = $this->marginLeft;
            $y = $height + $this->fontSize + $this->marginTop;
            if ($this->textAlign == 'center') {
                $x = ($widthWithLabel - $labelWidth) / 2;
            }
            // write text
            imagettftext($labelImage, $this->fontSize, 0, $x, $y, $black, $this->font, $this->text);
            // merge two image
            imagecopymerge($labelImage, $originImage, 0, 0, 0, 0, $width, $height, 100);
            ob_start();
            imagejpeg($labelImage);
            $image = ob_get_clean();
        } elseif (extension_loaded('imagick')) {
            // get origin image info
            $tmp = tempnam('cache/images', 'barcode_');
            if (file_put_contents($tmp, $image)) {
                $originImage = new \Imagick("barcode:$tmp");
                $width = $originImage->getImageWidth();
                $height = $originImage->getImageHeight();
                $heightWithLabel = $height + $this->fontSize + $this->marginBottom + $this->marginTop;
                $labelWidth = ($this->fontSize * strlen($this->text) / self::FONT_RATIO) + $this->marginLeft + $this->marginRight;
                $widthWithLabel = max($width, $labelWidth);
                // create a new image
                $labelImage = new \Imagick();
                $labelImage->newImage($widthWithLabel, $heightWithLabel, 'white', 'jpg');
                $draw = new \imagickdraw();
                // black font color
                $colorForeground = new \imagickpixel('rgb(0,0,0)');
                $draw->setFillColor($colorForeground);
                $draw->setFont($this->font);
                $draw->setFontSize($this->fontSize);
                $x = $this->marginLeft;
                $y = $height + $this->fontSize + $this->marginTop;
                if ($this->textAlign == 'center') {
                    $x = ($widthWithLabel - $labelWidth) / 2;
                }
                // write text
                $labelImage->annotateImage($draw, $x, $y, 0, $this->text);
                // merge two image
                $labelImage->compositeimage($originImage, \Imagick::COMPOSITE_COPY, 0, 0);
                ob_start();
                $labelImage->drawImage($draw);
                echo $labelImage;
                $image = ob_get_clean();
            }
        } else {
            throw new BarcodeException('Neither gd-lib or imagick are installed!');
        }
        return $image;

    }


    /**
     * PNG format Label
     * @param $image
     * @return string
     * @throws BarcodeException
     */
    private function labelPng($image)
    {
        if (function_exists('imagecreate')) {
            // get origin image info
            $originImage = imagecreatefromstring($image);
            $width = imagesx($originImage);
            $height = imagesy($originImage);
            // cal new image width and height
            $heightWithLabel = $height + $this->fontSize + $this->marginBottom + $this->marginTop;
            $labelWidth = ($this->fontSize * strlen($this->text) / self::FONT_RATIO) + $this->marginLeft + $this->marginRight;
            $widthWithLabel = max($width, $labelWidth);
            // create a new image
            $labelImage = imagecreate($widthWithLabel, $heightWithLabel);
            $colorBackground = imagecolorallocate($labelImage, 255, 255, 255);
            // background color background
            imagecolortransparent($labelImage, $colorBackground);
            // black font color
            $black = imagecolorallocate($labelImage, 0, 0, 0);
            // use font file
            $x = $this->marginLeft;
            $y = $height + $this->fontSize + $this->marginTop;
            if ($this->textAlign == 'center') {
                $x = ($widthWithLabel - $labelWidth) / 2;
            }
            // write text
            imagettftext($labelImage, $this->fontSize, 0, $x, $y, $black, $this->font, $this->text);
            // merge two image
            imagecopymerge($labelImage, $originImage, 0, 0, 0, 0, $width, $height, 100);
            ob_start();
            imagepng($labelImage);
            $image = ob_get_clean();
        } elseif (extension_loaded('imagick')) {
            // get origin image info
            $tmp = tempnam('cache/images', 'barcode_');
            if (file_put_contents($tmp, $image)) {
                $originImage = new \Imagick("barcode:$tmp");
                $width = $originImage->getImageWidth();
                $height = $originImage->getImageHeight();
                $heightWithLabel = $height + $this->fontSize + $this->marginBottom + $this->marginTop;
                $labelWidth = ($this->fontSize * strlen($this->text) / self::FONT_RATIO) + $this->marginLeft + $this->marginRight;
                $widthWithLabel = max($width, $labelWidth);
                // create a new image
                $labelImage = new \Imagick();
                $labelImage->newImage($widthWithLabel, $heightWithLabel, 'none', 'png');
                $draw = new \imagickdraw();
                // font color black
                $colorForeground = new \imagickpixel('rgb(0,0,0)');
                $draw->setFillColor($colorForeground);
                $draw->setFont($this->font);
                $draw->setFontSize($this->fontSize);
                $x = $this->marginLeft;
                $y = $height + $this->fontSize + $this->marginTop;
                if ($this->textAlign == 'center') {
                    $x = ($widthWithLabel - $labelWidth) / 2;
                }
                //  write text
                $labelImage->annotateImage($draw, $x, $y, 0, $this->text);
                // merge two image
                $labelImage->compositeimage($originImage, \Imagick::COMPOSITE_COPY, 0, 0);
                ob_start();
                $labelImage->drawImage($draw);
                echo $labelImage;
                $image = ob_get_clean();
            }
        } else {
            throw new BarcodeException('Neither gd-lib or imagick are installed!');
        }
        return $image;
    }


}