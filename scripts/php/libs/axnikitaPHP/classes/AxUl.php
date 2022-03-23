<?
  class AxUl extends AxElement {
    function __construct(...$items) {
      parent::__construct('ul');
      $this
        ->appendItems(...$items);
    }

    function appendItems(...$items) {

      foreach ($items as $item) {
        if(is_array($item)) {
          $this->appendItems($item);
        } else if(is_string($item) || is_numeric($item)) {
          $el = $this->createElement($item);
          $this->append($el);
        } else if($item->axComponent) {
          $this->append($item);
        }
      }
      return $this;
    }

    function createElement($item) {
      $el = new AxElement('li');
      $el->append($item);
      return $el;
    }
  }
?>