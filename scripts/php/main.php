<?
  require_once(__DIR__.'/../../config.php');
  require_once('libs/axnikitaPHP/axnikitaPHP.php');
  spl_autoload_register(function ($class_name) {
    $fileName = __DIR__.'/classes/' . $class_name . '.php';
    if (is_file($fileName)) {
      require_once($fileName);
    }
  });
?>