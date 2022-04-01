<?
class AxSmartList_Item {
  protected int $list_key;
  protected int $type_key;
  protected $type;
  protected $list;
  public $value;

  function __construct($value, object &$type, object &$list, int $key) {
    $this->value = $value;
    $this->type = &$type;
    $this->list = &$list;
    $this->list_key = $key;
    $this->type_key = $type->appendItem($this);
  }

  function kill() {
    unset($this->list[$key]);
  }
}
?>