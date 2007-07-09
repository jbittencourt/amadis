<?

/**
 * Menu box
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */

class AMMenuBox extends CMHTMLObj {

  /*
   * Pode-se adicionar uma pilha de strings html em um array 
   * ou um unico string html para ser que irah para a tela
   */
  public function add($item) {
    $this->contents[] = $item;
  }



  public function __toString() {
    global $_CMAPP;
    
    parent::add('<div class="menu-box">');
	parent::add('<div class="border-top"></div>');
	parent::add('<div class="content">');
    
	if(!empty($this->contents)) {
    	foreach($this->contents as $item) {
			parent::add($item);
      	}
    }

	parent::add('</div>');
	parent::add('<div class="border-bottom"></div>');
	parent::add('</div>');
    
	return parent::__toString();
  }
}