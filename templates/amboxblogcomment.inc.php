<?php

/**
 * The AMBoxDiarioComment is a box that list blog comments.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMBlog
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */
class AMBoxBlogComment extends CMHTMLObj 
{

    private $contents;

  /**
   * Pode-se adicionar uma pilha de strings html em um array 
   * ou um unico string html para ser que irah para a tela
   **/
    public function add($item) {
        $this->contents[] = $item;
    }


    function __toString() {
        global $_CMAPP;

        parent::add('<div class="diary-comment">');
        if(!empty($this->contents)) {
        	parent::add(implode('', $this->contents));
        }
        parent::add('</div>');
        
        return parent::__toString();
        
    }
}

