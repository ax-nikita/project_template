<?
  class AxPage extends AxComponent 
  {
    private $scripts = [];
    private $css = [];

    function __construct()
    {
      parent::__construct();

      $this
        ->baseScript = new AxComponent();
        
      $this
        ->baseScript
        ->append('console.log("AxNikita PHP '.AX_PHP_LIBS_VERSION.' '.AX_PHP_LIBS_DATE_UPDATE.'");');

      $this
        ->body = new AxElement('body');

      $this
        ->head = new AxHead();

      $this
        ->html = new AxElement('html');

      $this
        ->html->append($this->head, $this->body);
  
      $this
        ->lang('en')
        ->append('<!DOCTYPE html>', $this->html);

      return $this;
    }

    function title($title = null) 
    {
      if($title == null) 
      {
        return $this->head->title();
      } 
      else 
      {
        $this->head->title($title);
        return $this;
      }
    }

    function content()//Ошибка при повторном вызове
    { 
      if(AxDate::jsCode())
        $this
          ->baseScript->append(AxDate::jsCode());

      $this
        ->insertJS((new AxJS($this->baseScript->content(), false)));
        
      foreach ($this->scripts as $v) 
      {
        $this->body->append($v->element());
      };

      foreach ($this->css as $v) 
      {
        $this->head->append($v->element());
      };

      return $this->innerHTML();
    }

    function charset($charset = null) 
    {
      if($charset == null) 
      {
        return $this->head->charset();
      } 
      else 
      {
        $this->head->charset($charset);
        return $this;
      }
    }

    function lang($lang = null) 
    {
      if($lang == null) 
      {
        return $this->html->getAttribute('lang');
      } 
      else 
      {
        $this->html->setAttribute('lang', $lang);
        return $this;
      }
    }

    function insertJS(...$names) 
    {
      foreach ($names as $name) 
      {
        if(is_string($name))
        {
          $this->scripts[] = new AxJS($name, true);
        }
        else
        {
          $this->scripts[] = $name;
        }
      }
      return $this;
    }

    function insertCSS(...$names) 
    {
      foreach ($names as $name) {
        $this->css[] = new AxCSS($name);
      }
      
      return $this;
    }
  }
