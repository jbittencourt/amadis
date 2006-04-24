<?

class AMUserThumb extends AMThumb {

  public function __construct() {
    parent::__construct();
    $this->maxX = 60;
    $this->maxY = 60;
  }

  public function load() {
    $tmp = $this->codeArquivo;
    if(empty($tmp)) {
      $this->codeArquivo = AMUserFoto::DEFAULT_IMAGE;
    }
    return parent::load();
  }

}

?>