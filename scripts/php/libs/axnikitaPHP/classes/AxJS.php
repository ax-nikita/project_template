<?
  class AxJS
  {
    function __construct($nameOrScript, $load = true) 
    {
      $this->content = $nameOrScript;
      $this->load = $load;
    }
    function element() 
    {
      $element = new AxElement('script');

      if($this->load)
      {
        $element->setAttribute('src', $this->content);
      }
      else 
      {
        $element->axVal($this->compress());
      }

      if($this->_module) {
        $element->setAttribute('type', 'module');
      } else {
        $element->setAttribute('type', 'text/javascript');
      }
      
      return $element;
    }

    function module() {
      $this->_module = true;
      return $this;
    }

    function compress() {
      return  Ax::compressJS($this->content);
    }

    function __toString() {
      return $this->element();
    }
  }
?>