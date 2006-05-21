<?
/**  
 *  Relates files -> libraries
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMLibrary
 * @version 1.0
 * @author Cristiano S Basso <csbasso@lec.ufrgs.br>
 */

class AMLibraryFiles extends CMObj {

  public function configure() {
    $this->setTable("FilesLibraries");
    $this->addField("filesCode",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("time",CMObj::TYPE_INTEGER,20,1,0,0);
    $this->addField("libraryCode",CMObj::TYPE_INTEGER,11,1,0,0);
    $this->addField("referred",CMObj::TYPE_CHAR,1,1,0,0);
    $this->addField("active",CMObj::TYPE_CHAR,1,1,0,0);
    $this->addField("shared",CMObj::TYPE_CHAR,1,1,0,0);

    $this->addPrimaryKey("filesCode");
    $this->addPrimaryKey("libraryCode");
  }

  public function setActive(){
    $this->active = "y";
  }  
  public function unsetActive(){
    $this->active = "n";
  }
  public function setShared(){
    $this->shared = "y";
  }
  public function unsetShared(){
    $this->shared = "n";
  }
}

?>