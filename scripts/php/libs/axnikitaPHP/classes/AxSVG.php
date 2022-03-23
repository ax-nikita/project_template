<?
class AxSVG extends AxElement {
  function __construct($url) {
    parent::__construct('svg');
    $this
      ->setAttribute('src', $url);
  }
}
?>