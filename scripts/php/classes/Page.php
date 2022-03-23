<?
  class Page extends AxPage
  {
    function __construct() {
      parent::__construct();

      $this
        ->insertJS('/scripts/js/libs/axnikitaJS/axnikitaJSdew.js','/scripts/js/main.js')
        ->insertCSS('/css/axnikitaCSS_1.0.css', '/css/main.css');

      $this->header = new Header();
      $this->main = new Main();
      $this->footer = new Footer();

      $this
        ->body
          ->append($this->header, $this->main, $this->footer);
    }
  }
?>