<?
class AxDate 
{
  static function jsCode() {
    return "window.differenceDate = new Date() - Date.parse('".date('Y-m-d')."T".date('H:i:s')."Z"."');";
  } 

  static function js() 
  { 
    $script = new AxJS(AxDate::jsCode(), false);
    return $script;
  }

  function __construct($str, $t = 1) {
    $this->type = $t;
    $this->time = $str;
  }

  function element() {
    $element = new AxElement('div');

    $element
      ->setAttribute('data-id', 'ax_date')
      ->setAttribute('type', $this->type)
      ->axVal($this->time);

    return $element;
  } 

  function __toString() {
    return $this->element();
  }
}
?>