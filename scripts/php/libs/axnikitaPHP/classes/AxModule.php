<? 
class AxModule extends AxElement {
  function __construct($url, $tag = 'div') {
    parent::__construct($tag);
    $this
      ->setAttribute('domLoader', $url);
  }
}
?>