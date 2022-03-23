<? 
class AxElement extends AxComponent
{
  //Глобальные
  private const SINGLE_TAGS = [
    'doctype', 
    'area', 
    'base', 
    'br', 
    'col', 
    'embed', 
    'hr', 
    'img', 
    'input', 
    'keygen', 
    'link', 
    'meta', 
    'param', 
    'source', 
    'track', 
    'wbr'
  ];

  //Локальные
  private $attributeArray = [];

  function __construct($tag, $value = false) 
  {
    parent::__construct();
    if(preg_match('/[\[\.]/', $tag)) {
      $classes = [];
      $attributes = [];

      $classReg = '/\.[\w-]+/';
      $attributeReg = '/\[.+]/U';

      preg_match_all($classReg, $tag, $classes);
      $classes = $classes[0];
      $tag = str_replace($classes, '', $tag);

      preg_match_all($attributeReg, $tag, $attributes);
      $attributes = $attributes[0];
      $tag = str_replace($attributes, '', $tag);

      if(!empty($classes)) {
        array_walk($classes, function (&$v) {
          $v = substr($v, 1);
        });
        $this->setAttribute('class', implode(' ', $classes));
      }

      if(!empty($attributes)) {
        foreach ($attributes as $v) {
          $v = trim($v, '[]');
          $v = explode('=', $v);
          $this->setAttribute($v[0], trim($v[1], '"'));
        }        
      }

      if($value !== false) {
        if(is_array($value)) {
          $this->append(...$value);
        } else {
          $this->append($value);
        }
      }
    }

    $this->tag = mb_strtolower($tag);

    switch ($this->tag) 
    {
			case 'textarea':
			case 'input':
				$this->baseValue = 'value';
				break;
			case 'img':
				$this->baseValue = 'src';
				break;
			default:
        $this->baseValue = 'innerHTML';
				break;
		}
    return $this;
  }

  public function content()
  {
    return $this->otherHTML();
  }

  public function otherHTML()
  {
    $otherHTML = '';

    $leftTag = [$this->tag];
    $attributes = $this->attribute();

    if($attributes !== '') {
      $leftTag[] = $attributes;
    };

    $leftTagContent = implode(' ', $leftTag);

    if(in_array($this->tag, AxElement::SINGLE_TAGS)) 
    {
      $otherHTML = '<'.$leftTagContent." />";
    } 
    else 
    {
      $otherHTML = '<'.$leftTagContent.'>'.$this->innerHTML().'</'.$this->tag.">";
    }
    
    return $otherHTML;
  }

  private function attribute() {
    $attribute = [];
    foreach ($this->attributeArray as $k => $v) 
    {
      if ($v === null) {
        $attribute[] = $k;
      } else {
        $attribute[] = $k.'="'.$v.'"';
      }
    }
    return implode(' ', $attribute);
  }

  function setAttribute($name, $value = null) {
    $this->attributeArray[mb_strtolower($name)] = $value;
    return $this;
  }

  function getAttribute($name) {
    return $this->attributeArray[mb_strtolower($name)];
  }

  function removeAttribute($name) {
    unset($this->attributeArray[mb_strtolower($name)]);
    return $this;
  }

  function removeAllAttributes()
  {
    $this->attributeArray = [];
    return $this;
  }

  function getStyleArray() {
    return explode(';', $this->getAttribute('style'));
  }

  function getStyleValue($property) {
    $style = $this->getStyleArray();
    foreach ($style as $v) {
      $data = explode(':', $v);
      if($data[0] == $property) {
        return $data[1];
      }
    }
    return false;
  }

  function setStyleValue($property, $value) {
    $style = $this->getStyleArray();
    if($style[0] != "") 
    {
      foreach ($style as $k => $v) 
      {
        $data = explode(':', $v);
        if($data[0] == $property) 
        {
          $data[1] = $value;
          $style[$k] = implode(':', $data);
          $this->setAttribute('style', implode(';', $style));
          return $this;
        }
      }
    }
    else 
    {
      return $this->setAttribute('style', $property.':'.$value);
    }
    $style[] = $property.':'.$value;
    $this->setAttribute('style', implode(';', $style));
    return $this;
  }
  

  function style($property = null, $value = null) 
  {
    if($property === null) {
      return $this->getAttribute('style');
    } 
    else if ($value === null)
    {
      if(preg_match("/^[a-z\-]+$/",$property))
      {
        return $this.getStyleValue($property);
      }
      else 
      {
        return $this->setAttribute('style', $property);
      }
    } 
    else
    { 
      if(preg_match("/^[a-z\-]+$/", $property))
      {
        return $this->setStyleValue($property, $value);
      } 
      else 
      {
        return false;
      }
    }
  }

  function axVal($value = null) {
    if($value === null) 
    {
      if($this->baseValue == 'innerHTML') 
      {
        return $this->innerHTML();
      }
      else 
      {
        return $this->getAttribute($this->baseValue);
      }
    } 
    else 
    {
      if($this->baseValue == 'innerHTML') 
      {
        $this->valueArray = [$value];
      }
      else 
      {
        $this->setAttribute($this->baseValue, $value);
      }
    }
    return $this;
  }

  function replaceWith($element)
  {
    $oldThis = $this;
    $oldThis = $element;
    return $oldThis;
  }

  function axClass($class = null) {
		if($class === null) 
    {
      return $this->getAttribute('class');
    } else {
      $this->setAttribute('class', $class);
    }
		return $this;
	}

  function addClass($class) {
    $classList = explode(' ', $this->getAttribute('class'));
    if($classList[0] != "") {
      if(!in_array($class, $classList)) {
        $classList[] = $class;
      }
      $class = implode(' ', $classList);
    } 
    return $this->setAttribute('class', $class);
  }

  function removeClass($class) {
    $classList = explode(' ', $this->getAttribute('class'));
    $index = array_search($class, $classList);
    if($index !== false) { 
      unset($classList[$index]);
      $this->setAttribute('class', implode(' ', $classList));
    }
    return $this;
  }

  function axAttribute($name, $value = null) {
		if ($value === $null) 
    {
			return $this->getAttribute($name);
		} 
    else 
    {
			return $this->setAttribute($name, $value);
		}
	}

  function querySelector($selector) {
    $selectorArray = explode(" ", $selector);
  }
}