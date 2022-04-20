<?
class Ax { 
  private const ALL_VAR_TYPES = [
    'boolean',
    'integer',
    'double',
    'string',
    'array',
    'object',
    'resource',
    'NULL',
    'unknown type'
  ];

  static function getMemoryString() {
    $memx_max = round(memory_get_peak_usage() / 1024, 2);
    $mem_string_max = '';
    if ($memx_max > 1024) {
      $mem_string_max = round($memx_max / 1024, 2) . ' МБ.';
    } else {
      $mem_string_max = $memx_max . ' кБ.';
    }
    return 'Макс памяти использавано:' . $mem_string_max;
  }

  static function consolLog($message, $return = false) {
    if ($return) {
      return date("Y-m-d H:i:s ") . $message . "\n";
    } else {
      echo date("Y-m-d H:i:s ") . $message . "\n";
    }

    return true;
  }

  static function genRandomString($l = 10) {
    $chars = "qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
    $s = StrLen($chars) - 1;
    $str = null;
    while ($l--) {
      $str .= $chars[rand(0, $s)];
    }

    return $str;
  }

  static function getRandomKey($array) {
    $summ = 0;
    $summ2 = 0;
    foreach ($array as $k => $v) {
      if ($v < 0) {
        $array[$k] = 0;
      }

      $summ += $array[$k];
    }
    $rand = rand(1, $summ);
    foreach ($array as $k => $v) {
      $summ2 += $v;
      if ($summ2 >= $rand) {
        return $k;
      }

    }
  }

  static function testStr($str, $min, $max, $reg = 'all+') {
    $m = [];
    if (!isset($str) || !is_string($str) || empty($str)) {
      return false;
    }

    preg_match(self::ezReg($min, $max, $reg), $str, $m);
    if ($m[0] == $str) {
      return true;
    }

    return false;
  }

  static function is_assoc_array(array $arr) {
    $c = 0;
    foreach (array_keys($arr) as $val) {
      if($val != $c++) {
        return true;
      } 
    }
    return false;
  }

  static function compressJS($script) {

    preg_match_all('/(\'.*\'|\".*\")/U', $script, $strings);

    $strings = $strings[0];

    $replace_string_array = [];

    for ($i = 0; $i < sizeof($strings); $i++) {
      if (preg_match('/(\s|\/)/', $strings[$i]) == 0) {
        array_splice($strings, $i, 1);
        $i--;
      } else {
        $replace_string_array[] = "#ax#$i#ax#";
      }
    }

    $script = str_replace($strings, $replace_string_array, $script);

    $script = preg_replace("/((\s|\n|\r)+|\/(\/|\*).*?($|\n|\r)|\/\*.*?\*\/)/", ' ', $script);

    $search = [];
    $replace = [];

    $chars = [';', '(', ')', '{', '}', '+', '=', ',', '<', '>', ':', '-', '*', '/', '|', '&', '%', '^', '!'];
    foreach ($chars as $char) {
      $search[] = "/\s*\\$char\s*/";
      $replace[] = $char;
    }

    $script = preg_replace($search, $replace, $script);
    $script = str_replace($replace_string_array, $strings, $script);

    return $script;
  }

  static function ezReg($min, $max, $type) {
    $str = '/';
    switch ($type) {
    case 'num':
      $str .= "[0-9]{" . $min . "," . $max . "}/u";
      break;
    case 'eng':
      $str .= "[a-zA-Z0-9]{" . $min . "," . $max . "}/u";
      break;
    case 'eng-':
      $str .= "[a-zA-Z]{" . $min . "," . $max . "}/u";
      break;
    case 'all':
      $str .= "[a-zA-Zа-яА-ЯёЁ\d]{" . $min . "," . $max . "}/u";
      break;
    case 'all+':
      $str .= "[а-яА-ЯёЁ#:;\+\-=()\-\w\t\s,.?!\"«»]{" . $min . "," . $max . "}/u";
      break;
    case 'allS':
      $str .= "[a-zA-Zа-яА-ЯёЁ\"«»\-\s\d]{" . $min . "," . $max . "}/u";
      break;
    case 'password':
      $str .= "[a-zA-Zа-яА-ЯёЁ\d_#:;\+\-=()\-,.?!\"«»]{" . $min . "," . $max . "}/u";
      break;
    case 'mail':
      $str .= ".+@.+\.[a-z]{2,}/u";
      break;
    }
    return $str;
  }
}

?>