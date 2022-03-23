<? 
//В разработке
class AxCompressor
{ 
  function __construct($type) {
    $modified = 0;
    $offset = 60 * 60 * 24 * 7;
    if($type == 'js') {
      header ('Content-type: text/javascript; charset=UTF-8');
      header ('vary: accept-encoding');
      header ('Cache-Control: max-age=' . $offset);
      header ('Pragma:');
      header ("Last-Modified: ".gmdate("D, d M Y H:i:s", $modified )." GMT");
    }
    $this->content = '';
  }


  function getCompress() {
    return Ax::compressJS($this->content);
  }

  function addDIR($dir) {
    $files = scandir($dir);

    $files = array_filter($files, function ($v) {
      return (preg_match('/.+\.js$/i', $v) === 1);
    });
    
    foreach ($files as $v) {
      $this->content .= file_get_contents($dir.'/'.$v);
    }
    return $this;
  }

  function addFile($name) {
    $this->content .= file_get_contents($name);
    return $this;
  }

  function addString($str) {
    $this->content .= $str;
    return $this;
  }

  function __toString() {
    return $this->getCompress();
  }
}
?>