<?

include("cmwebservice/cmwemail.inc.php");

class AMMailMessage extends CMWSimpleMail {
  private $ammessage;

  public function __construct() {
    global $_conf;
    parent::__construct("AMADIS",(string) $_conf->app->general->admin_email);
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
