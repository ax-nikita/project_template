<?
class AxMenuItem extends AxElement {
  function __construct($title = null, $menu = null) {
    parent::__construct('li');
    $this->a = new AxElement('a');
    $this->append($this->a);
    $this->href('#');

    if ($menu !== null) {
      $this->appendMenu($menu);
    }

    $this->title($title);
  }

  function appendMenu($menu) { //Ошибка при повторнов ызове
    $this->menu = $menu;
    if ($this->menu === null) {
      $this->prepend($this->menu);
    }
    $this->addClass('item-with-menu');
    $this->menu->addClass('sub-menu');
    return $this;
  }

  function title($title = null) {
    if ($title === null) {
      return $this->a->axVal();
    } else {
      $this->a->axVal($title);
      return $this;
    };
  }

  function href($href = null) {
    if ($href === null) {
      return $this->a->getAttribute('href');
    } else {
      $this->a->setAttribute('href', $href);
      return $this;
    };
  }
}
?>