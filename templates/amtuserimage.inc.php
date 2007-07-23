<?php
/**
 * The default vizualization of the user picture.
 *
 * This class render a user picture inside a gigsaw.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access  private
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMFoto, AMFile, AMUserFoto
 */
class AMTUserImage extends AMImageTemplate {

  	public function __toString() {
    	global $_CMAPP;

    	parent::add('<div class="user-image">');
    	parent::add('<img src="'.$this->getImageURL().'" class="box" alt="" />');
    	parent::add('</div>');
    
    	return parent::__toString();
  	} 
}