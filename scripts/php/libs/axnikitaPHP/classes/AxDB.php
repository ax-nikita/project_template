<?
  class AxDB 
  {
    private static $auto_connect_update = true; 

    private const OPTION = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    static function autoConnect (bool $autoConnect) 
    {
      AxDB::$auto_connect_update = $autoConnect;
    }

    private static function arrayToDB($array, $con = "'") 
    {
      if(!is_array($array))
      {
        return "($con$array$con)";
      } 
      else if(!is_array($array[0])) 
      {
        return "($con".implode("$con,$con", $array)."$con)";
      }
      else 
      {
        $str = [];
        foreach ($array as $v) 
        {
          if(!is_array($v))
            return false;

          $str[] = "($con".implode("$con,$con", $v)."$con)";
        }
        return implode(',', $str);
      }
    }

    public function __construct(string $dbhost, string $dbuser, string $dbpass, string $dbname)
    {
      $this->host = $dbhost;
      $this->user = $dbuser;
      $this->password = $dbpass;
      $this->name = $dbname;
      $this->charset = 'utf8';
      $this->option = self::OPTION;
      $this->db = false;
    }

    public function setCharset($charset) {
      $this->charset = $charset;
      return $this;
    }

    public function setOption($option) {
      $this->option = $option;
      return $this;
    }

    public function connect() 
    {
      $this->db = new PDO(
        $this->dsn(), 
        $this->user, 
        $this->password, 
        $this->option
      );
      return $this;
    }

    private function dsn() 
    { 
      $dbhost = $this->host;
      $dbname = $this->name;
      $charset = $this->charset;
      return "mysql:host=$dbhost;dbname=$dbname;charset=$charset";
    }

    public function add($table, $array, $params_start = '') 
    {
      $valList = [];
  
      if(is_array($params_start))
        $params = $this->arrayToDB($params_start, '`');
      else {
        if(is_array(reset($array)))
          $params_start = array_keys(reset($array));
        else  
          $params_start = array_keys($array);
        $params = $this->arrayToDB($params_start, '`');
      }
      
      if(is_array(reset($array))){
        $str1 = [];
        foreach ($array as $k => $v) {
          $str2 = [];
          foreach ($params_start as $v2) {
            $str2[] = "?";
            $valList[] = $v[$v2];
          }
          $str1[] = "(".implode(',', $str2).")";
        }
        $v = implode(',', $str1);
      } else if(is_array($array)){
        $str = [];
        foreach ($array as $v) {
          $str[] = "?";
          $valList[] = $v;
        }
        $v = "(".implode(',', $str).")";
      } else {
        $v = "(?)";
        $valList[] = $array;
      }

      $sqlzp = $this->db->prepare("INSERT INTO `$table`$params VALUES $v");
      try {
        $sqlzp->execute($valList);
      } 
      catch (Exception $e) {
        return false;
      }
      return $this->db->lastInsertId();
    }

    public function getCustom($sqlzp, $param = '')
    {
      $sqlzp = $this->db->prepare($sqlzp);
      if($param == '')
        $sqlzp->execute();
      else 
        $sqlzp->execute($param);
      return $sqlzp->fetchAll();
    }

    public function get($table, $arr = '', $param = '', $limit = '', $order = '', $joinOrder ='') 
    {
      $str = '';
      $valList = [];
      $join = '';
      $tKey = 0;
      $d = '';

      if (is_array($table))
      {
        $contactParam = $arr;
        $arr = $param;
        $param = $limit;
        $limit = $order;
        $order = $joinOrder;
        $d = '`t0`.';
    
        $joinTable = array_slice($table, 1); 
        $table = $table[0];
        $join = " AS `t".$tKey++.'`';
        foreach ($joinTable as $k => $v) 
        {
          $personKey = $tKey++;
          reset($contactParam);
          $kp = key($contactParam);
          $vp = $contactParam[$kp];
          unset($contactParam[$kp]);
          $join .= " LEFT JOIN `$v` AS `t$personKey` ON  `t0`.`$kp` = `t$personKey`.`$vp`";
        }
      } 
    
      if(is_array($param))
      {
        foreach($param as $v)
        {
          $parametr = explode(' AS ', $v);
          $v = '`'.$parametr[0].'`';
          if(isset($parametr[1]))
            $v .= ' AS '.$parametr[1];
        } 
        $param = implode(',', $param);
      } 
      else 
      {
        if($param != '')
        {
          $parametr = explode(' AS ', $param);
          $param = '`'.$parametr[0].'`';
          if(isset($parametr[1]))
            $param .= ' AS '.$parametr[1];
        }
        else
          $param = '*';
      }
    
      if (is_array($arr)) 
      {
        $str2 = [];
        foreach ($arr as $k => $v) 
        {
          if(is_array($v)) 
          {
            $val = [];
            foreach ($v as $v2) 
            {
              $val[] = " ?";
              $valList[] = $v2;
            }
            $str2[] = "$d`$k` IN (".implode(',', $val).")";
          } 
          else 
          {
            $str2[] = "$d`$k` = ?";
            $valList[] =  $v;
          }
        }
        $arr = " WHERE ".implode(' AND ' ,$str2);
      } 
      else 
      {
        if ($arr != '')
        {
          $valList[] = $arr;
          $arr = " WHERE $d`id` = ? ";
          $limit = 1;
        } 
      }
    
      if ($order != '')
      {
        $type = substr($order, -1);
        $order = " ORDER BY `".substr($order, 0, -1)."` ";
        if ($type == '-')
          $order.='DESC';
        else if ($type == '+')
          $order.='ASC';
        else 
          return false;
      }
    
      if($limit != '')
        $limit = ' LIMIT '.$limit;
      
      $sqlzp = $this->query("SELECT $param FROM `$table`$join$arr$order$limit", $valList);
      return $sqlzp->fetchAll();
    }

    public function query($query, $valList) 
    {
      $sqlzp = $this->db->prepare($query);
      $sqlzp->execute($valList);
      return $sqlzp;
    }

    public function update($table, $arrParam, $mP = 'id') {
      $str = '';
      $arrU = [];
      $mPArr = [];
      $valList = [];
      $jorno = [];
      if(empty($arrParam))
      return false;
      foreach (array_keys($arrParam[0]) as $k => $v) {
        if($v == $mP)
          continue;
        $str = "`$v` = CASE `$mP` ";
        $mparr = [];
        foreach ($arrParam as $k2 => $v2) {
          $str .= "WHEN ? THEN ? ";
          $valList[] = $v2[$mP];
          $valList[] = $v2[$v];
          if(!isset($mPArr[0])){
            $mparr[] = '?';
            $jorno[] = $v2[$mP];
          }
        }
        if(!isset($mPArr[0]))
        $mPArr = $mparr;
        $str .= 'END';
        $arrU[] = $str;
      }
      $str = "`$table`.`$mP` IN (".implode(',', $mPArr).")";

      foreach($jorno as $v)
        $valList[] = $v;

      $str2 = implode(',', $arrU);
      $sqlzp = $this->query("UPDATE `$table` SET $str2 WHERE $str", $valList);
      return true;
    }

    public function dell($table, $arrParam, $exactly = false) 
    {
      $valList = [];
      if($exactly)
      {
        $pre = "DELETE FROM `$table` WHERE ";
        $str = [];
        if(is_array(reset($arrParam)))
        {
          foreach($arrParam as $k => $v)
          {
            $str2 = []; 
            foreach($v as $k2 => $v2)
            {
              $str2[] = "`$k2` = ? ";
              $valList[] = $v2;
            }
            $str[] = $pre.implode(' AND ', $str2);
          }
          $sqlzp = implode('; ', $str);
        }
        else if(is_array($arrParam))
        {
          foreach($arrParam as $k => $v)
          {
            if(is_array($v))
              return false;
            $str[] = "`$k` = ? ";
            $valList[] = $v;
          }
          $sqlzp = $pre.implode(' AND ', $str);
        } 
        else 
        {
          $sqlzp = $pre."`id` = ?";
          $valList[] = $arrParam;
        }
      } 
      else 
      {
        $str = "DELETE FROM `$table` WHERE ";
        if(is_array(reset($arrParam)))
        {
          $str2 = [];
          foreach(array_keys(reset($arrParam)) as $v)
          {
            $str3 = [];
            foreach($arrParam as $v1)
            {
              if(!array_key_exists($v, $v1))
                return false;
              $str3[] = '?';
              $valList[] = $v1[$v];
            }
            $str2[] = "`$v` IN (".implode(',', $str3).")";
          }
        } 
        else if(is_array($arrParam)) 
        {
          $str2 = [];
          foreach($arrParam as $k => $v)
          {
            $valka = [];
            if(is_array($v))
            {
              foreach ($v as $v2) 
              {
                $valka[] = '?';
                $valList[] = $v2;
              }
              $str2[] = "`$k` IN (".implode(',', $valka).")";
            } 
            else 
            {
              $str2[] = "`$k` = ?";
              $valList[] = $v;
            }
          }
        } 
        else 
        {
          $str2[] = "`id` = ?";
          $valList[] = $arrParam;
        }
        $sqlzp = $str.implode(' AND ', $str2);
      }
      $sqlzp = explode(';', $sqlzp);
      foreach ($sqlzp as $k => $v)
      {
        if(is_array(reset($arrParam)) && $exactly)
        {
          $size = sizeof(reset($arrParam));
          $personList = array_slice($valList, $k * $size, $size);
          $sqlzp1 = $this->db->prepare($v);
          try 
          {
            $sqlzp1->execute($personList);
          } 
          catch (Throwable $e) 
          {
            return false;
          }   
        } 
        else 
        {
          $sqlzp1 = $this->db->prepare($v);
          try 
          {
            $sqlzp1->execute($valList);
          } 
          catch (Throwable $e) 
          {
            return false;
          }        
        }
      }
      return true;
    }
  }
?>