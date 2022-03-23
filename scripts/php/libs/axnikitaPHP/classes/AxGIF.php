<?
class AxGIF_farme {
  function __construct($frame, $delay = 0.032) {
    if (is_string($frame)) {
      $this->image = new Imagick($frame);
    } else if (is_object($frame) && get_class($frame) == 'Imagick') {
      $this->image = &$frame;
    } else {
      echo 'errAppend AxGIF: ';
      var_dump($frame);
      exit;
    }
    $this->delay = $delay;
    $this->width = $this->image->getImageWidth();
    $this->height = $this->image->getImageHeight();
    $this->x = 0;
    $this->y = 0;
    $this->background = $this->image->getImageBackgroundColor();
  }
}

class AxGIF {
  function __construct() {
    $this->frames = [];
  }

  function build($width = false, $height = 0) {
    $animation = new Imagick();

    if ($width == false) {
      $width = $this->frames[0]->width;
    }

    $animation->setFormat("gif");

    foreach ($this->frames as $frame) {
      $animation->newImage($frame->width, $frame->height, $frame->background);
      $animation->compositeImage($frame->image, imagick::COMPOSITE_DEFAULT, $frame->x, $frame->y);

      $animation->thumbnailImage($width, $height);
      $animation->setImageDelay($frame->delay);
    }

    return $animation;
  }

  function appendFrames(...$frames) {

  }

  function appendFrame($frame, $delay = 0.032) {
    return $this->frames[] = new AxGIF_farme($frame, $delay);
  }
}

?>