<?
  class AxHead extends AxElement
  {
    private $metaElement = [];

    function __construct() {
      parent::__construct('head');

      $this
        ->createMetaElement('charset');

      $this
        ->createMetaElement('viewport')
        ->setAttribute('name', 'viewport');

      $this
        ->createHttpEquiv('X-UA-Compatible', 'IE=edge');

      $this
        ->titleElement = new AxElement('title');

      $this
        ->viewport('width=device-width, initial-scale=1.0')
        ->charset('UTF-8')
        ->title("Test")
        ->build();
  
      return $this;
    }

    function createMetaElement($name) {
      $this->metaElement[$name] = new AxElement('meta');
      return $this->metaElement[$name];
    }

    function createHttpEquiv ($createHttpEquiv, $content) {
      $element = $this->createMetaElement('http-equiv'.'_'.$createHttpEquiv);

      $element
        ->setAttribute('http-equiv', $createHttpEquiv)
        ->setAttribute('content', $content);

      return $element;
    }

    function removeHttpEquiv ($createHttpEquiv) {
      unset($this->metaElement['http-equiv'.'_'.$createHttpEquiv]);
      return $this;
    }

    function setMetaParam($name, $attribute, $value = null) {
      if($value === null) 
      {
        return $this->metaElement[$name]->getAttribute($attribute);
      } 
      else 
      {
        $this->metaElement[$name]->setAttribute($attribute, $value);
      }
      return $this;
    }

    function build() {
      $clearArray = array_values($this->metaElement);
      $clearArray[] = $this->titleElement;

      $this
        ->removeValue()
        ->append(...$clearArray);

      return $this;
    }

    function charset($charset = null) {
      return $this->setMetaParam('charset', 'charset',$charset);
    }

    function viewport($viewport = null) {
      return $this->setMetaParam('viewport', 'content', $viewport);
    }

    function title($title = null) {
      $this->titleElement->axVal($title);
      return $this;
    }
  }
?>
