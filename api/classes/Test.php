<?
class Test_api {
  function execute($data) {
    $this->api_data = [
      'result' => 'Test_api работает!',
      'error' => false,
    ];
    return $this->api_data;
  }
}
?>