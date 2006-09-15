<?

/**
 * Agregator keywords filters.
 * 
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMAgregator
 * @version 1.0
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 */

class AMAgregator extends CMObj {

    public function configure() {
    $this->setTable("Agregator");

    $this->addField("codeSource",CMObj::TYPE_INTEGER,4,1,0,0);
    $this->addField("keywords",CMObj::TYPE_TEXT,65535,1,0,0);
    $this->addField("time", CMObj::TYPE_INTEGER, 4,1,0,0);
    
    $this->addPrimaryKey("codeSource");
    
  }
}
?> 