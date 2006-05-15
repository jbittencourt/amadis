<?
/**
 * @package AMADIS
 * @subpackage AMTemplates
 */

include("cmwebservice/cmwemail.inc.php");

class AMMailMessage extends CMWSimpleMail {
  private $ammessage;

  public function __construct() {
    global $_conf;
    parent::__construct((string) $_conf->app->general->admin_email,"AMADIS");
  }

  public function setMessage($message) {
    $this->ammessage = $message;
  }

  public function send() {
    parent::setMessage($this->ammessage);
    parent::send();
  }

}

?>
