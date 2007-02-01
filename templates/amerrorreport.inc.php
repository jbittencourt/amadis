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
   * @param string $class The class and method what executed in the moment.
   * @param string $module The module where the action is executed.
   **/
  public function __construct(CMException $e, $class, $module) {
    parent::__construct();
    $this->exception = $e;
    new AMLog($class, $this->exception->getMessage(), $module);
    
  }

  public function __toString() {
    global $_language, $_CMAPP;

    parent::add($_language['fatal_error']);
    return parent::__toString();
  }

}
?>