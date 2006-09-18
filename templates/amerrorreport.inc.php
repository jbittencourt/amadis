<?
/**
 * Implements an inteface object that is showed to the user when an critical error occurs
 * 
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @subpackage AMStateMessages
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @package AMADIS
 **/

class AMErrorReport extends CMHTMLObj {

  const QUERY_ERROR=0;
  
  protected $exception;

  /**
   * @param object $e The exception that caused the error
   **/
  public function __construct(CMException $e, $class, $type=self::QUERY_ERROR) {
    parent::__construct();
    $this->exception = $e;
    new AMLog($class, $this->exception->getMessage());
    
  }

  public function __toString() {
    global $_language, $_CMAPP;

    parent::add($_language['fatal_error']);
    return parent::__toString();
  }

}
?>