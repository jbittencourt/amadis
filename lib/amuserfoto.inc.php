<?php
/**
 * Encapsulates the User Picture.
 *
 * This class implements the picture of a user. It extend the AMFoto superclass that 
 * resize the picture to the scale defined in the constructor of this method. The
 * abstract method getView() return an AMTUserImage that renders an default vizualization
 * of the picture.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access  private
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMFoto, AMArquivo, AMTUserImage
 */
class AMUserFoto extends AMFoto {

  const DEFAULT_IMAGE = 1;     //the default user image is coded with number 1 in the databse

  public function __construct() {
    parent::__construct();

    $this->maxX = 100;
    $this->maxY = 100;
    
  }

  public function getView() {

    switch($this->state) {
    case CMObj::STATE_PERSISTENT:
      return new AMTUserImage($this->codeFile);
    case CMObj::STATE_DIRTY:
      return new AMTUserImage($this,AMImageTemplate::METHOD_SESSION);
    case CMObj::STATE_DIRTY_NEW:
      return new AMTUserImage($this,AMImageTemplate::METHOD_SESSION);
    case CMObj::STATE_NEW:
      return new AMTUserImage(self::DEFAULT_IMAGE);
    }
  }



}