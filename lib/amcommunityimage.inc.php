<?

/**
 * An implementation of AMFoto to the Projects.
 * 
 * This class implements an representation of project image, setting
 * the maxX and maxY. The getView() function returns an AMTProjectImage.
 *
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMFoto
 **/
class AMCommunityImage extends AMFoto {

  public function __construct() {
    parent::__construct();

    $this->maxX = 145;
    $this->maxY = 135;
  }

  public function getView() {
    if($this->state==CMObj::STATE_PERSISTENT) {
      return new AMTCommunityImage($this->codeArquivo);
    }
    else {
      return new AMTCommunityImage($this,AMImageTemplate::METHOD_SESSION);
    }
  }


}



?>