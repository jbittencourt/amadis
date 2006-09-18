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
 * @subpkage AMStateMessages
 **/

class AMError {

  /**
   * @staticvar array $errors - list of errors that will logged
   */
  static protected $errors = array();

  /**
   *
   * @param String $message  Error message
   * @param String $thrower  The script that is generating the error.
   * @param Exception $e     The exception that caused the error.
   */
  public function __construct($message,$thrower, $e='') {
    
    self::$errors[] = array("message"=>$message,
			    "thrower"=>$thrower,
			    "exception"=>$e
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