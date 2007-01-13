<?
/**
 * @package AMADIS
 * @subpackage AMTemplates
 */

include("cminterface/widgets/cmwtreenode.inc.php");

class AMTree extends CMWTreeNode {
  
  public function __construct($caption) {
    global $_CMAPP;
    parent::__construct($caption);
    $this->setNoBullets("","");
  } 

  public function noBullets() {
  }

  public function __toString(){
    return parent::__toString();
  }

}