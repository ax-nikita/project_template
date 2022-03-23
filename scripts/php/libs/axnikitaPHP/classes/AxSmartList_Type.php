<? 
  class AxSmartList_MapPoint {

    private string $name;
    private string $filterFunction;
    private string $varType;
    private array $items = [];
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