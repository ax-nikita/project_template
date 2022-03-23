<?
  class Header extends AxElement {
    function __construct() {
      parent::__construct('header');
      $this->nav = new AxElement('nav');
      $this->menu = new AxMenu();
      $this->nav->append($this->menu);
      $this->append($this->nav);

      $this
        ->addMenuItem('Главная', '/')
        ->addMenuItem('Дополнительная страница', 'example-page');
    }

    function addMenuItem($title, $href) {
      $index = new AxMenuItem($title);
      $index->href($href);
      $index->a->setAttribute('spa');

      $this->menu->append($index, $example);

      return $this;
    }
  }
?>