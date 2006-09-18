<?

/**
 * Implements an standart interface to display alerts to the user.
 *
 * This class is used by any object in the system to display
 * alert to the user. The messages can be retrived
 * by any class, an exibed to the user. Usualy AMMain is reponsable for
 * show the messages. 
 * 
 * @package AMADIS
 * @subpackage AMStateMessages
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 *
 * @see AMMessage, AMError
 **/

class AMAlert {

  static protected $alerts = array();

  public function __construct($message) {
    self::$alerts[] = $message;
  }

  public static function getMessages() {
    return self::$alerts;
  }

}

?>