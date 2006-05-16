<?
/**
 * Log messages error, this is a better way to control error events of the system.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public or private
 * @package AMADIS
 * @subpackage AMErrorReport
 * @version 1.0
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 * @see AMErrorReport,AMError
 */
class AMLog {

  
  /**
   * @staticvar array $errors - list of errors that will logged
   */
  static public $errors = array();

  /**
   * Add a new error in error.log array, after execute AMError::commit() to save in log file.
   *
   * @param String $message - Error message
   * @param String $class - CSS style to user message
   * @param String $e - Exception message throw to CMDevel or AMAPI
   */
  public function __construct($class, $e) {
    
    self::$errors[] = array("thrower"=>$class,
			    "exception"=>$e
			    );
  }

  /**
   * Register error messages in error.log file
   *
   * @access public 
   * @static
   * @param void
   * @return void
   */
  public static function commit() {
    global $_conf;
    $path = (string) $_conf->app[0]->paths[0]->log;

    $errors = self::getErrors();
    if(!empty($errors)) {
      @$flog = fopen($path, "a");
      $errs = array();
      foreach($errors as $e) {
	$h = "3";// Hour for time zone goes here e.g. +7 or -4, just remove the + or -
	$hm = $h * 60;
	$ms = $hm * 60;
	$gmdate = gmdate("M d Y H:i:s ", time()-($ms)); // the "-" can be switched to a plus if that's what your time zone is.
	$errs[] = "$gmdate - CLASS:$e[thrower]:Exception:$e[exception]\n";
      }
      @fwrite($flog, implode("\n", $errs));
      @fclose($flog);
    }
  }

  public static function getErrors() {
    return self::$errors;
  }
}

?>
