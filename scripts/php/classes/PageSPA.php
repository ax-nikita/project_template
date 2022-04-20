<?
  class PageSPA {
    function __construct($title) {
      
      if(!isset($_GET[AX_JS_CONFIG['get_page']])) {
        $page = new Page();
        $main = $page->main;
        $page->title($title);
        $page->ajax_request = false;
      } else {
        $page = new AxComponent();
        $titleElement = new AxElement('title');
        $main = new Main();
        $page->main = $main;

        $titleElement->axVal($title);

        $page->append($titleElement, $page->main);
        $page->ajax_request = true;
      }
      $this->page = $page;
    }
  }
?>