<?
  class AxLog 
  {
    function __construct($dir = 'log.txt') 
    {
      $this->dir = $dir;
      $this->contents = false;
    }

    function log($message, $type = null) 
    {
      if($type === null)
        $type = 'LOG';
      $log = fopen($this->dir, "a");

      if($this->contents === false) 
      {
        $this->contents = fread($log, filesize($this->dir));
      };
      $newString = Ax::consolLog($type.'::'.$message, true);
      if($log !== false) 
      {
        fwrite($log, $newString);
        fclose($log);
        return true;
      }
      return false;
    }

    function get() 
    {
      if($this->contents === false) 
      {
        $lines = $this->contents = file($this->dir);
      } 
      else 
      {
        $lines = $this->contents;
      }
      $arr = [];
      foreach ($lines as $v) 
      {
        $date = explode(' ', substr($v, 0, 19));  
        $message = explode('::',substr($v, 19));  
        $arr[] = [
          'date' => $date[0],
          'time' => $date[1],
          'type' => $message[0],
          'text' => $message[1],
        ];
      }
      return $arr;
    }
  }
  
?>