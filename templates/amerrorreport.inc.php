<?
/**
 * Implements an inteface object that is showed to the user when an critical error occurs
 * 
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @package AMADIS
 **/

class AMErrorReport extends CMHTMLObj {

  const QUERY_ERROR=0;
  
  protected $exception;

  /**
   * @param object $e The exception that caused the error
   **/
  public function __construct(CMException $e,$type=self::QUERY_ERROR) {
    parent::__construct();
    $this->exception = $e;
  }

  public function __toString() {
    global $_language, $_CMAPP;

    parent::add($_language['fatal_error']);
    return parent::__toString();
  }

}
?>