<?
/* 
  В разработке, 
*/
class AxImageMG {
  static $imgTypes = [
    'jpeg',
    'jpg',
    'png',
    'gif',
    'bmp',
    'avif',
    'gd',
    'gd2',
    'wbmp',
    'webp',
    'xbm',
  ];

  function __construct($dir = false) {
    $this->dir = $dir;
    $this->loadImage();
  }

  function loadImage() {
    $this->image = new Imagick($dir);

    return $this;
  }

  function compressImage($maxSize = 1920) {
    $width = $this->image->getImageWidth();
    $height = $this->image->getImageHeight();
    $max = max($height, $width);

    if ($max > $maxSize) {
      $focus = $max / $maxSize;
      $newWidth = $width / $focus;
      $newheight = $height / $focus;
      $this->image->scaleImage($newWidth, $newheight);
    }

    return $this;
  }

  function goByPixels($func) {
    $width = $this->image->getImageWidth();
    $height = $this->image->getImageHeight();

    if (!is_array($func)) {
      $func = [$func];
    }

    for ($posX = 0; $posX < $width; $posX++) {
      for ($posY = 0; $posY < $height; $posY++) {
        foreach ($func as $f) {
          $f($this, $posX, $posY);
        }
      }
    }

    return $this;
  }

  function imageMask($mask) {
    if (is_string($mask)) {
      $mask = new AxImageMG($mask);
    } else if (get_class($mask) == 'Imagick') {
      $image = $mask;
      $mask = new AxImageMG();
      $mask->image = $image;
    }

    $mask->maxDark = 765;
    $mask->darkColor = [];

    $mask->goByPixels([
      function ($img, $x, $y) {
        $color = $pixel->getColor(2);

        $colorSumm = $color['r'] + $color['g'] + $color['b'];
        if ($color['a'] != 127 && $colorSumm < $mask->maxDark) {
          $mask->maxDark = $colorSumm;
          $mask->darkColor = $color;
        }
      },
    ]);

    $this->mask = $mask;
    $this->newImage = $this->clearImage($this->width, $this->height);

    $this->goByPixels([
      function ($img, $x, $y) {
        $mask = $this->mask->image;
        $maxDark = $this->mask->maxDark;

        $colorIndex = imagecolorat($img->image, $x, $y);
        $imageColor = imagecolorsforindex($img->image, $colorIndex);

        $colorIndex = imagecolorat($mask, $x, $y);
        $maskColor = imagecolorsforindex($mask, $colorIndex);

        $colorSumm = $maskColor['red'] + $maskColor['green'] + $maskColor['blue'];

        if ($colorSumm - 150 > $maxDark && $imageColor['alpha'] != 127) {
          $newColor = [
            'red' => ($maskColor['red'] + $imageColor['red']) / 2,
            'green' => ($maskColor['green'] + $imageColor['green']) / 2,
            'blue' => ($maskColor['blue'] + $imageColor['blue']) / 2,
          ];
        } else {
          $newColor = $maskColor;
        }

        if ($maskColor['alpha'] != 127) {
          $colorIndex = imagecolorallocatealpha($this->newImage, $newColor['red'], $newColor['green'], $newColor['blue'], $maskColor['alpha']);
          imagesetpixel($this->newImage, $x, $y, $colorIndex);
        }
      },
    ]);

    unset($this->mask);
    $this->image = $this->newImage;
    unset($this->newImage);

    return $this;
  }

  static function clearImage($width = 100, $height = false, $color = false) {
    if ($height === false) {
      $height = $width;
    }

    $img = imagecreatetruecolor($width, $height);
    imagealphablending($img, false);

    if ($color == false) {
      $col = imagecolorallocatealpha($img, 255, 255, 255, 127);
      imagefilledrectangle($img, 0, 0, $width, $height, $col);
      imagealphablending($img, true);
      imagesavealpha($img, true);
    } else {
      $col = imagecolorallocatealpha($img, ...$color);
      imagefilledrectangle($img, 0, 0, $width, $height, $col);
      imagealphablending($img, true);
      imagesavealpha($img, true);
    }

    return $img;
  }

  function width() {
    return imagesx($this->image);
  }

  function height() {
    return imagesy($this->image);
  }

  function sizeUp($size = 5) {
    $this->sizeUp_size = $size;
    $this->newImg = $this->clearImage($this->width() * $size, $this->height() * $size);

    $this->goByPixels([
      function ($img, $x, $y) {
        $upsize = $this->sizeUp_size;

        $colorIndex = imagecolorat($this->image, $x, $y);
        $imageColor = imagecolorsforindex($this->image, $colorIndex);
        if ($imageColor['alpha'] != 127) {
          for ($i = 0; $i < $upsize; $i++) {
            for ($j = 0; $j < $upsize; $j++) {
              $colorIndex = imagecolorallocatealpha($this->newImg, $imageColor['red'], $imageColor['green'], $imageColor['blue'], $imageColor['alpha']);
              imagesetpixel($this->newImg, $x * $upsize + $i, $y * $upsize + $j, $colorIndex);
            }
          }
        }
      },
    ]);

    unset($this->sizeUp_size);
    $this->image = $this->newImg;
    unset($this->newImg);

    return $this;
  }

  function normalazeImage($width = 100, $height = false, $color = false) {
    if ($height === false) {
      $height = $width;
    }

    $newImg = $this->clearImage($width, $height, $color);

    imagefilter($newImg, IMG_FILTER_SCATTER, 10, 100);
    $widthCenter_1 = $width / 2;
    $heightCenter_1 = $height / 2;

    $widthCenter_2 = $this->width() / 2;
    $heightCenter_2 = $this->height() / 2;

    $factor_width = $widthCenter_1 - $widthCenter_2;
    $factor_height = $heightCenter_1 - $heightCenter_2;

    imagecopy($newImg, $this->image, $factor_width, $factor_height, 0, 0, $this->width(), $this->height());
    imagesavealpha($newImg, true);

    $this->image = $newImg;

    return $this;
  }

  function rotate($deg = 90) {
    $this->image = imagerotate($this->image, $deg, 0);
    return $this;
  }

  function flip() {
    imageflip($this->image, IMG_FLIP_HORIZONTAL);
    return $this;
  }
}
?>