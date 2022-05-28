<?php

namespace ModStart\Misc\Captcha;

use Exception;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Hashing\BcryptHasher as Hasher;
use Illuminate\Session\Store as Session;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;


class Captcha
{

    
    protected $files;

    
    protected $config;

    
    protected $imageManager;

    
    protected $session;

    
    protected $hasher;

    
    protected $str;

    
    protected $canvas;

    
    protected $image;

    
    protected $backgrounds = [];

    
    protected $fonts = [];

    
    protected $fontColors = [];

    
    protected $length = 5;

    
    protected $width = 120;

    
    protected $height = 36;

    
    protected $angle = 15;

    
    protected $lines = 3;

    
    protected $characters;

    
    protected $text;

    
    protected $contrast = 0;

    
    protected $quality = 90;

    
    protected $sharpen = 0;

    
    protected $blur = 0;

    
    protected $bgImage = true;

    
    protected $bgColor = '#ffffff';

    
    protected $invert = false;

    
    protected $sensitive = false;

    
    protected $textLeftPadding = 4;

    
    public function __construct(
        Filesystem $files,
        Repository $config,
        ImageManager $imageManager,
        Session $session,
        Hasher $hasher,
        Str $str
    )
    {
        $this->files = $files;
        $this->config = $config;
        $this->imageManager = $imageManager;
        $this->session = $session;
        $this->hasher = $hasher;
        $this->str = $str;
        $this->characters = config('captcha.characters', '2346789abcdefghjmnpqrtuxyzABCDEFGHJMNPQRTUXYZ');
    }

    
    protected function configure($config)
    {
        if ($this->config->has('captcha.' . $config)) {
            foreach ($this->config->get('captcha.' . $config) as $key => $val) {
                $this->{$key} = $val;
            }
        }
    }

    
    public function create($config = 'default', $api = false)
    {
        $this->backgrounds = $this->files->files(__DIR__ . '/../../../resources/misc/captcha/backgrounds');
        $this->fonts = $this->files->files(__DIR__ . '/../../../resources/misc/captcha/fonts');

        if (app()->version() >= 5.5) {
            $this->fonts = array_map(function ($file) {
                return $file->getPathName();
            }, $this->fonts);
        }

        $this->fonts = array_values($this->fonts); 
        $this->configure($config);

        $generator = $this->generate($config);
        $this->text = $generator['value'];

        $this->canvas = $this->imageManager->canvas(
            $this->width,
            $this->height,
            $this->bgColor
        );

        if ($this->bgImage) {
            $this->image = $this->imageManager->make($this->background())->resize(
                $this->width,
                $this->height
            );
            $this->canvas->insert($this->image);
        } else {
            $this->image = $this->canvas;
        }

        if ($this->contrast != 0) {
            $this->image->contrast($this->contrast);
        }

        $this->text();

        $this->lines();

        if ($this->sharpen) {
            $this->image->sharpen($this->sharpen);
        }
        if ($this->invert) {
            $this->image->invert($this->invert);
        }
        if ($this->blur) {
            $this->image->blur($this->blur);
        }

        return $api ? [
            'sensitive' => $generator['sensitive'],
            'key' => $generator['key'],
            'img' => $this->image->encode('data-url')->encoded
        ] : $this->image->response('png', $this->quality);
    }

    
    protected function background()
    {
        return $this->backgrounds[rand(0, count($this->backgrounds) - 1)];
    }

    
    protected function generate($config)
    {
        switch ($config) {
            case 'formula':
                $number1 = rand(20, 80);
                $number2 = rand(0, 10);
                $calc = rand(0, 99) > 50 ? '+' : '-';
                switch ($calc) {
                    case '+':
                        $bag = $number1 + $number2;
                        break;
                    case '-':
                        $bag = $number1 - $number2;
                        break;
                }
                $bagValue = $number1 . $calc . $number2 . '=?';
                $this->length = strlen($bagValue);
                break;
            default:
                $characters = str_split($this->characters);
                $bag = '';
                for ($i = 0; $i < $this->length; $i++) {
                    $bag .= $characters[rand(0, count($characters) - 1)];
                }
                $bagValue = $bag;
                break;
        }

        $bag = $this->sensitive ? $bag : $this->str->lower($bag);

        $hash = $this->hasher->make($bag);
        $this->session->put('captcha', $bag);
        return [
            'value' => $bagValue,
            'key' => $bag
        ];
    }

    
    protected function text()
    {
        $marginTop = $this->image->height() / $this->length;

        $i = 0;
        foreach (str_split($this->text) as $char) {
            $marginLeft = $this->textLeftPadding + ($i * ($this->image->width() - $this->textLeftPadding) / $this->length);

            $marginTopChar = $marginTop;
            if ($char == '-' || $char == '+' || $char == '=') {
                $marginTopChar *= 2;
            }

            $this->image->text($char, $marginLeft, $marginTopChar, function ($font) {
                $font->file($this->font());
                $font->size($this->fontSize());
                $font->color($this->fontColor());
                $font->align('left');
                $font->valign('top');
                $font->angle($this->angle());
            });

            $i++;
        }
    }

    
    protected function font()
    {
        return $this->fonts[rand(0, count($this->fonts) - 1)];
    }

    
    protected function fontSize()
    {
        return rand($this->image->height() - 10, $this->image->height());
    }

    
    protected function fontColor()
    {
        if (!empty($this->fontColors)) {
            $color = $this->fontColors[rand(0, count($this->fontColors) - 1)];
        } else {
            $color = [rand(0, 255), rand(0, 255), rand(0, 255)];
        }

        return $color;
    }

    
    protected function angle()
    {
        return rand((-1 * $this->angle), $this->angle);
    }

    
    protected function lines()
    {
        for ($i = 0; $i <= $this->lines; $i++) {
            $this->image->line(
                rand(0, $this->image->width()) + $i * rand(0, $this->image->height()),
                rand(0, $this->image->height()),
                rand(0, $this->image->width()),
                rand(0, $this->image->height()),
                function ($draw) {
                    $draw->color($this->fontColor());
                }
            );
        }
        return $this->image;
    }

    
    public function check($value)
    {
        if (!$this->session->has('captcha')) {
            return false;
        }

                $key = $this->session->get('captcha');
        $sensitive = $this->session->get('captcha.sensitive');

        if (!$sensitive) {
            $value = $this->str->lower($value);
        }

        $this->session->remove('captcha');

                return strtolower($value) == strtolower($key);
    }

    
    public function check_api($value, $key)
    {
        return $this->hasher->check($value, $key);
    }

    
    public function src($config = null)
    {
        return url('captcha' . ($config ? '/' . $config : '/default')) . '?' . $this->str->random(8);
    }

    
    public function img($config = null, $attrs = [])
    {
        $attrs_str = '';
        foreach ($attrs as $attr => $value) {
            if ($attr == 'src') {
                                continue;
            }
            $attrs_str .= $attr . '="' . $value . '" ';
        }
        return '<img src="' . $this->src($config) . '" ' . trim($attrs_str) . '>';
    }

}
