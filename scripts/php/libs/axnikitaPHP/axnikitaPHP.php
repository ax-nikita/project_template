<? 
define("AX_PHP_LIBS_VERSION", "3.01");
define("AX_PHP_LIBS_DATE_UPDATE", "24.03.22");
spl_autoload_register(function ($class_name) {
  $fileName = __DIR__.'/classes/' . $class_name . '.php';
  if (is_file($fileName)) {
    require_once($fileName);
  }
});
//AxSmartList НЕ ИСПОЛЬЗОВАТЬ
?>