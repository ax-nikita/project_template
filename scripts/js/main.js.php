<?
require_once __DIR__ . '/../../scripts/php/main.php';

$compressor = new AxCompressor('js');
$compressor->addDIR(__DIR__ . '/classes/');
$compressor->addDIR(__DIR__ . '/main/');
$compressor->addDIR(__DIR__);
$compressor->addString('window.axconfig=JSON.parse(\'' . json_encode(AX_JS_CONFIG) . '\');');
if (isset($_SESSION['logged_user']['id'])) {
  if (!isset($_SESSION['logged_user']['chosenPage'])) {
    $_SESSION['logged_user']['chosenPage'] = 1;
  }
  $compressor->addString('window.myChosenPage=' . $_SESSION['logged_user']['chosenPage'] . ';');
}

echo $compressor->getCompress();
?>