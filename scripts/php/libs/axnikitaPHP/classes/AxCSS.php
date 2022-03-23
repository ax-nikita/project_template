<?
class AxCSS
{
  function __construct($name) 
  {
    $this->content = $name;
    $this->load = $load;
  }
  function element() 
  {
    $element = new AxElement('link');
    $element
      ->setAttribute('href', $this->content)
      ->setAttribute('rel', 'stylesheet');
    return $element;
  }
}
?>