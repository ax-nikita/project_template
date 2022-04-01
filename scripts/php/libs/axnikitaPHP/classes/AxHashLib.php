<?
class AxHashLib {
  function __construct($parentDir) {
    $this->parentDir = $parentDir;
  }

  function saveFile($fileName, $fileDeep = 2) {
    $hash = hash_file('md5', $fileName);
    $step = ceil(strlen($hash) / $fileDeep);

    $arrayHash = str_split($hash, $step);

    $type = preg_match('/\.(.*)$/U', $fileName, $matches) ? $matches[0] : '';

    $fileName = array_pop($arrayHash);

    mkdir($this->parentDir . '/' . implode('/', $arrayHash), 0777, true);

    $file_dir = $this->parentDir . '/' . implode('/', $arrayHash) . '/' . $fileName . $type;

    return $file_dir;
  }
}
?>