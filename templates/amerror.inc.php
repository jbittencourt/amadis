<?

/**
 * Implements an standart interface to report error to the user.
 *
 * This class is used by any object in the system to report error
 * messages or just messages to the user. The messages can be retrived
 * by any class, an exibed to the user. Usualy AMMain is reponsable for
 * show the messages. 
 * 
 * @access public
 * @param $message - Error message
 * @param $class - CSS style to user message
 * @param $e - Exception message throw for CMDevel or AMAPI
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @package AMADIS
 * @subpkage AMError
 **/

class AMError {

  /**
   * @staticvar array $errors - list of errors that will logged
   */
  static protected $errors = array();

  public function __construct($message,$class, $e) {

    self::$errors[] = array("message"=>$message,
			    "thrower"=>$class,
			    "exception"=>$e);
  }

  /**
   * Register errors message in erro.log file
   *
   * @access public 
   * @static
   * @param void
   * @return void
   */
  public static function commit() {
    global $_conf;
    $path = (string) $_conf->app[0]->paths[0]->log;
    
    if(!empty(self::$errors)) {
      @$flog = fopen($path, "a");
      $errs = array();
      foreach(self::$errors as $e) {
	$h = "3";// Hour for time zone goes here e.g. +7 or -4, just remove the + or -
	$hm = $h * 60;
	$ms = $hm * 60;
	$gmdate = gmdate("M d Y H:i:s ", time()-($ms)); // the "-" can be switched to a plus if that's what your time zone is.
	$errs[] = "$gmdate - MESSAGE:$e[message]|Exception:$e[exception]\n";
      }
      @fwrite($flog, implode("\n", $errs));
      @fclose($flog);
    }
  }

  /**
   * @access public 
   * @static
   * @param void
   * @return array $errors - List of the errors loggeds
   */
  public static function getErrors() {
    return self::$errors;
  }

}

?>