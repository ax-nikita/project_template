<?
  require_once('scripts/php/main.php');
  $title = 'Главная';

  $page = (new PageSPA($title))->page;
  $main = $page->main;

  if(!$page->ajax_request){
    $page
      ->body
        ->style('padding', '10px');
  }

  $main
    ->style('background', '#DDF')
    ->style('border-radius', '10px')
    ->style('padding', '10px')
    ->style('display', 'grid')
    ->style('gap', '10px')
    ->style('margin', '10px 0');

  $header = new AxElement('h1');
  $header->axVal('Страница - <strong>'.$title.'</strong>');
  $main->append($header);

  $p = new AxElement('p');
  $p->axVal('Значимость этих проблем настолько очевидна, что реализация намеченных плановых заданий представляет 
  собой интересный эксперимент проверки форм развития. Значимость этих проблем настолько очевидна, что реализация 
  намеченных плановых заданий обеспечивает широкому кругу (специалистов) участие в формировании позиций, занимаемых 
  участниками в отношении поставленных задач. Задача организации, в особенности же рамки и место обучения кадров 
  играет важную роль в формировании систем массового участия. Разнообразный и богатый опыт рамки и место обучения 
  кадров позволяет оценить значение новых предложений.');
  $main->append($p);

  $module = new AxElement('div');
  $module->setAttribute('domLoader', '/moduls/example');
  $main->append($module);

  $main->append(Ax::getMemoryString());

  $page->print();
?>