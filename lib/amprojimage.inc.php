<?php
/**
 * An implementation of AMFoto to the Projects
 * 
 * This class implements an representation of project image, setting
 * the maxX and maxY. The getView() function returns an AMTProjectImage.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access  private
 * @package AMADIS
 * @subpackage AMProject
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMFoto,AMTCommunityImage
 **/
class AMProjImage extends AMFoto {

  const DEFAULT_IMAGE = 2;     //the default user image is coded with number 2 in the databse


  public function __construct() {
    parent::__construct();

    $this->maxX = 145;
    $this->maxY = 135;
  }

  public function getView() {
    switch($this->state) {
    case CMObj::STATE_DIRTY:
      return new AMTProjectImage($this,AMImageTemplate::METHOD_SESSION);
    case CMObj::STATE_DIRTY_NEW:
      return new AMTProjectImage($this,AMImageTemplate::METHOD_SESSION);
    case CMObj::STATE_PERSISTENT:
	return new AMTProjectImage($this->codeArquivo);
    case CMObj::STATE_NEW:
      return new AMTProjectImage(self::DEFAULT_IMAGE);
    }
  }


}