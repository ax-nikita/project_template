<?
  class AxCURL
  {    
    function __construct($url, $type = "GET") {
      $this->ssl = false;
      $this->type = $type;
      $this->url = $url;
      $this->cookie = [];
    }

    function setSSL($ssl) {
      $this->ssl = $ssl;
      return $this;
    }

    function execute($data = []) {
      unset($ch);
      try {
        if($this->type == "GET") {
          $ch = curl_init($this->url.'?'.http_build_query($data));
        } else if($this->type == "POST") {
          $ch = curl_init($this->url);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data, '', '&amp;'));
        } else {
          echo 'ERROR: AxCURL - wrong type!';
          return;
        }

        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        
        if(!empty($this->cookie)) {
          curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: ".http_build_query($this->cookie, '', ';')));
          curl_setopt($ch, CURLOPT_COOKIEJAR, "cookies.txt");
        }
  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->ssl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $this->ssl);
  
        curl_setopt($ch, CURLOPT_HEADER, false);
        $html = curl_exec($ch);
  
        if ($html === false) {
          throw new Exception(curl_error($ch), curl_errno($ch));
        } 
      } catch (\Throwable $th) {
        trigger_error(sprintf(
          'Curl failed with error #%d: %s',
          $th->getCode(), $th->getMessage()),
          E_USER_ERROR);
      } finally {
        if (is_resource($ch)) {
          curl_close($ch);
        }
      }

      return $html;
    }

    function setCookie($array) {
      $this->cookie = $array;
    }
  }
?>