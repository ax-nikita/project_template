<? 
  class Base_api
  {
    static public $authorization = false;
    static public $admin = false;
    static public $api_data = '';
    function execute($data) {
      $this->api_data = $data.' успех';
      return $this->api_data;
    }
  }
  
?>