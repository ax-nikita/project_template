<? 
  class AxSmartList_MapPoint {
    protected string $name;
    protected string $filterFunction;
    protected string $varType;
    protected array $items = [];
    protected int $key = 0;
    public $value;

    function __construct($options) {
      foreach($options as $key => $value) {
        $this->{$key} = $value;
      } 
    }

    function setFilterFunction(string $functionName) {
      if($this->filterFunction != $functionName) {
        $this->filterFunction = $functionName;
        $this->filterItems();
      }
    }

    function setFilterType(string $type) {
      if($this->varType != $type && in_array($type, Ax::ALL_VAR_TYPES)) {
        $this->varType = $type;
        $this->filterItems();
      } 
    }

    function appendItem(object &$item) {
      $key = $this->key++;
      $this->items[$key] = &$item;
      return $key;
    }

    function filterItems() {
      foreach ($this->items as $key => &$item) {
        
        if($item === null) {
          unset($this->items[$key]);
          continue;
        }

        $value = $item->getValue();

        if (
          (isset($this->filterFunction) && !{$this->filterFunction}($value)) ||
          (isset($this->varType) && gettype($value) != $this->varType)
          ) {
          $this->items[$key]->kill();
        } 
      }
    }
  }
?>