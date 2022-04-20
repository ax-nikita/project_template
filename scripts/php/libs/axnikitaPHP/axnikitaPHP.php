<?
define("AX_PHP_LIBS_VERSION", "3.01");
define("AX_PHP_LIBS_DATE_UPDATE", "24.03.22");

spl_autoload_register(function ($class_name) {
  if (($class_name[0] . $class_name[1] == 'Ax' && ctype_upper($class_name[2])) || $class_name == 'Ax') {
    $searchArray = explode('_', $class_name);
    $size = sizeof($searchArray);
    if ($size == 1) {
      $fileName = __DIR__ . '/classes/' . $class_name . '.php';
    } else if ($size == 2) {
      $fileName = __DIR__ . '/classes/parts/' . $searchArray[0] . '/' . $class_name . '.php';
    }
    if (isset($fileName)) {
      require_once ($fileName);
    } else {
      exit('Undifiend AxClass:' . $class_name);
    }
  }
});
?>