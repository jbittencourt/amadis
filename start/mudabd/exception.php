<?

class FatalException extends Exception {};
class ErrorException extends Exception {};
class WarningException extends Exception {};

function exceptionErrorHandler($errno, $errstr, $errfile, $errline) {
  switch ($errno) {
  case E_USER_ERROR:
    throw new FatalException($errstr, $errno);
    break;
  case E_USER_WARNING:
  case E_WARNING:
    throw new WarningException($errstr, $errno);
    break;
  case E_USER_NOTICE:
    //throw new WarningException($errstr, $errno);
    break;
  default:
    echo " - Unknown error - <b>$errstr</b>($errno) <br>";
    break;
  }
  //return true;
}

$old_error_handler = null;

function install_exception_errorhandler() {
  global $old_error_handler;
  $old_error_handler = set_error_handler(exceptionErrorHandler);
}

function uninstall_exception_errorhandler() {
  global $old_error_handler;
  set_error_handler($old_error_handler);
  $old_error_handler = null;
}

// returns formatted info-dump of an exception
function exception_dump(&$e) {
  $res = "";
  $res .= "<br><h2>".get_class($e)."</h2>\n";
  $res .= "<h3>{$e->getMessage()} ({$e->getCode()})</h3>\n\n";
  $res .= "file: {$e->getFile()}<br/>\n";
  $res .= "line: {$e->getLine()}<br/>\n";
  $res .= "<PRE>";
  $res .= $e->getTraceAsString();
  $res .= "</PRE><br>";
  return $res;
}

?>