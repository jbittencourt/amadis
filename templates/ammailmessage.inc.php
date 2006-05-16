<?

/**
 * Front end to send an email.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see CMWSimpleMail
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
