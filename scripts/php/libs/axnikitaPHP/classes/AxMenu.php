<?
class AxMenu extends AxUl {
  function __construct(...$items) {
    parent::__construct();
    $this
      ->addClass('menu');
  }

  function createElement($item) {
    return new AxMenuItem($item);
  }
}
?>