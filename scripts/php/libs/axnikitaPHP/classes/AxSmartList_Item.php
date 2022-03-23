<? 
  class AxSmartList_Item {
    function __construct($map_points) {
      if(Ax::)
      $this->map_points = $map_points;
      $this->map = [];
    }

    function setMapPoint(string $point, $value = 0) {
      $this->map_point[$point] = $value;
    }

    function removeMapPoint(string $point) {
      unset($this->map_point[$point]);
    }

    function getLastPoint() {
      return &$this->map[array_key_last($this->map)];
    }

    function getLastPointValue() {
      return $this->map[array_key_last($this->map)];
    }

    function getFirstPoint() {
      return &$this->map[array_key_first($this->map)];
    }

    function getFirstPointValue() {
      return $this->map[array_key_first($this->map)];
    }

    function addPoint($type, $value) {
      $this->map = new AxSmartList_Item($this, $type, $value);
    }
  }
?>