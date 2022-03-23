<? 
class AxComponent
{
  protected $valueArray = [];

  public $axComponent = true;

  function __construct() 
  {
    return $this;
  }

  function content()
  {
    return $this->innerHTML();
  }

  function innerHTML() 
  {
    
    $innerHTML = '';
    
    foreach ($this->valueArray as $k => $v) 
    {
      if(!is_array($v)) {
        $innerHTML .= $v;
      } else {
        $innerHTML .= implode('', $v);
      }
    }

    return $innerHTML;
  }

  
  function append(...$value)
  {
    foreach ($value as $v)
    {
      $this->valueArray[] = $v;
    };
    return $this;
  }

  function prepend(...$value)
  {
    array_unshift($this->valueArray, ...$value);
    return $this;
  }

  function removeValue() 
  {
    $this->valueArray[] = [];
    return $this;
  }

  function print() {
    echo $this->content();
  }

  function __toString() {
    return $this->content();
  }
}
?>