<? 
/* 
  #в работе
*/
  class AxSmartList {

    public array $types = [];
    public array $list = [];
    public int $key = 0;

    function __construct(array $map) {
      if(Ax::is_assoc_array($map)) {
        foreach ($map as $type => $option) {
          $this->setType($type, (is_array($option)) ? $option : ['value' => $option]);
        }
      } else {
        foreach ($map as $type) {
          $this->setType($type);
        }
      }
    }

    function setType(string $type, array $option = []) {
      $this->types[$type] = new AxSmartList_Type(['name' => $type] + $option, $this);
    }

    function removeType(string $type) {
      $this->types[$type]->kill();
    }

    function getType(string $type) {
      return &$this->types[$type];
    }

    function addItem($value, string $type = '') {
      $type = $this->getType($type);
      if(isset($type)) {
        $key = $this->key++;
        $this->list[$key] = new AxSmartList_Item($value, $type, $this, $key);
        return $key;
      } 
      return false;
    }

    function getItem(int $key) {
      return $this->list[$key];
    }
  }
?>