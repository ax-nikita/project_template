<?
class API {
  function __construct($apiName, $data) {
    try {
      require_once 'Base.php';
      require_once $apiName . '.php';
      $apiName = $apiName . '_api';

      if ($apiName->authorization && !$CS->isUser()) {
        API::error_p();
      }

      if ($apiName->admin && !$CS->isAdmin()) {
        API::error_p();
      }

      $this->script = new $apiName();
      $this->data = $this->script->execute(json_decode($data, true));

    } catch (\Throwable $th) {
      echo $th;
      API::error();
    }
  }

  static function error() {
    echo 'возможно ваш запрос содержит ошибки.';
    exit;
  }

  static function error_p() {
    echo 'отказано в доступе.';
    exit;
  }

  function print() {
    echo json_encode($this->data, JSON_UNESCAPED_UNICODE);
  }
}
?>