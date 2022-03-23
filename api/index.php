<?
  require_once('../scripts/php/main.php');
  require_once('classes/api.php');
  if(empty($_POST)) {
    API::error();
  } else {
    $api = new API($_POST['type'], $_POST['data']);
    $api->print();
  }
?>