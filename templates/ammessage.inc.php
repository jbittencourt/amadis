<?

/**
 * Implements an standart interface to report messages to the user.
 *
 * This class is used by any object in the system to report 
 * messages to the user. The messages can be retrived
 * by any class, an exibed to the user. Usualy AMMain is reponsable for
 * show the messages. 
 * 
 * @package AMADIS
 * @subpackage AMStateMessages
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 **/

class AMMessage {

  static protected $errors = array();

  public function __construct($message,$class) {
    self::$errors[] = array("message"=>$message,
			    "thrower"=>$class);
  }

  public static function getMessages() {
    return self::$errors;
  }

}

?>