<?


class AMUserFoto extends AMFoto {

  private $default_image = 1;     //the default user image is coded with number 1 in the databse

  public function __construct() {
    parent::__construct();

    $this->maxX = 100;
    $this->maxY = 100;
    
  }

  public function getView() {
    switch($this->state) {
    case CMObj::STATE_PERSISTENT:
      return new AMTUserImage($this->codeArquivo);
      break;
    case CMObj::STATE_DIRTY:
      return new AMTUserImage($this,AMImageTemplate::METHOD_SESSION);
    case CMObj::STATE_NEW:
      if(empty($this->dados)) {
	return new AMTUserImage($this->default_image);
      }
      else {
	return new AMTUserImage($this,AMImageTemplate::METHOD_SESSION);
      }

    }
  }



}