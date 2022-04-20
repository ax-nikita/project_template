<?
  class AxLang {

    const MAIN_LANG = 'en';

    const LANG_NAMES = [
      'en' => 'English',
      'ru' => 'Русский',
      'fr' => 'Français',
      'be' => 'Беларуская',
      'uk' => 'Український'
    ];

    private static $lang_extends = [
      'be' => [
        'ru'
      ],
      'uk' => [
        'ru'
      ]
    ];

    const GLOSSARIES_DIR = __DIR__.'/../../../resources/glossaries/';

    static $glossarys = [];
    static $lang;

    static function spot() {
      $lang_keys = array_keys(static::LANG_NAMES);
      if($_COOKIE['lang'] == NULL || in_array($_COOKIE['lang'], $lang_keys) === false) {
        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        if(array_search($lang, $lang_keys) !== false){
          setcookie ('lang', $lang, 0 ,'/');
        } else {
          setcookie ('lang', static::MAIN_LANG, 0 ,'/');
          $lang = static::MAIN_LANG;
        } 
      } else {
        $lang = $_COOKIE['lang'];
      }
      static::$lang = $lang;
    }

    static function getOurLangs() {
      $langs = [];
      foreach (static::LANG_NAMES as $key => $value) {
        if($key !== static::$lang)
          $langs[$key] = $value;
      }
      return $langs;
    }
    
    static function getLang() {
      return static::$lang;
    }

    static function getDir($lang) {
      return static::GLOSSARIES_DIR.'glossary_'.$lang.'.json';
    }

    static function getLangName() {
      return static::LANG_NAMES[static::$lang];
    }

    private static function loadGlossary($lang) {
      $filename = static::getDir($lang);
      if(is_file($filename)) {
        static::$glossarys[$lang] = json_decode(file_get_contents($filename), true);
      } else {
        static::$glossarys[$lang] = [];
      }
    }

    private static function saveGlossary($lang) {
      $filename = static::getDir($lang);

      $file = fopen($filename, "w+");
      fwrite($file, json_encode(static::$glossarys[$lang], JSON_UNESCAPED_UNICODE));
      fclose($file);
    }

    static function registerTranslate($type, $key, $obj) {
      if(!static::checkGlossary(static::$lang, $type, $key, $obj)) {
        foreach (static::getOurLangs() as $k => $v) {
          static::checkGlossary($k, $type, $key, $obj);
        }
      }
      return static::$glossarys[static::$lang][$type][$key];
    }

    static function getTranslate($type, $key) {
      if(!isset(static::$glossarys[static::$lang])) {
        static::loadGlossary(static::$lang);
      }
      return static::$glossarys[static::$lang][$type][$key];
    }

    private static function obgGetValue($lang, $obj) {
      $value;
      if($obj[$lang] === null) {
        $famaly_lang = null;

        if(isset(static::$lang_extends[$lang])) {
          foreach (static::$lang_extends[$lang] as $v) {
            if($famaly_lang !== null)
              continue;
            
            if($obj[$v] !== null) {
              $famaly_lang = $v;
            }
          }
        }

        if($famaly_lang !== null) {
          $value = $obj[$famaly_lang];
        } else if($obj[static::MAIN_LANG] !== null) {
          $value = $obj[static::MAIN_LANG];
        } else {
          $value = reset($obj);
        }
      } else {
        $value = $obj[$lang];
      }
      return $value; 
    }

    private static function checkGlossary($lang, $type, $key, $obj) {
      if(!isset(static::$glossarys[$lang])) {
        static::loadGlossary($lang);
      }

      $glossary = static::$glossarys[$lang];
      
      if(isset($glossary[$type])) {
        if(!isset($glossary[$type][$key])) {
          static::$glossarys[$lang][$type][$key] = static::obgGetValue($lang, $obj);
          static::saveGlossary($lang);
          return false;
        }
      } else {
        static::$glossarys[$lang][$type] = [];
        static::$glossarys[$lang][$type][$key] = static::obgGetValue($lang, $obj);
        static::saveGlossary($lang);
        return false;
      }
      return true;
    }
  }
?>