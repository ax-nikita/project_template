<? 
  class AxSmartList {

    public array $types = [];
    public array $list = [];

    function __construct(array $map) {
      if(Ax::is_assoc_array($map)) {
        foreach ($map as $type => $option) {
          $this->setType($type, (is_array($option)) ? $option : ['value' => $option]);
        }
      } else {
        foreach ($map as $type) {
          $this->setMapPoint($type);
        }
      }
    }

    function setType(string $type, array $option = []) {
      $this->types[$type] = new AxSmartList_Type(['name' => $type] + $option);
    }

    function removeType(string $type) {
      unset($this->types[$type]);
    }

    function getType(string $type) {
      return &$this->types[$type];
    }
  }
?>