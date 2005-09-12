<?

/**
 * An implementation of AMFoto to the Diary.
 * 
 * This class implements an representation of the diary image, setting
 * the maxX and maxY. The getView() function returns an AMTProjectImage.
 *
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMFoto
 **/
class AMDiaryImage extends AMFoto {

  public function __construct() {
    parent::__construct();

    $this->maxX = 87;
    $this->maxY = 94;
  }

  public function getView() {
    if($this->state==CMObj::STATE_PERSISTENT) {
      return new AMTDiaryImage($this->codeArquivo);
    }
    else {
      return new AMTDiaryImage($this,AMImageTemplate::METHOD_SESSION);
    }
  }


}



?>