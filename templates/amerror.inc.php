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
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @package AMADIS
 * @subpkage AMErrorReport
 **/

class AMError {

  /**
   * @staticvar array $errors - list of errors that will logged
   */
  static protected $errors = array();

  /**
   *
   * @param String $message - Error message
   * @param String $class - CSS style to user message
   */
  public function __construct($message,$class, $e='') {
    
    self::$errors[] = array("message"=>$message,
			    "thrower"=>$class,
			    );
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