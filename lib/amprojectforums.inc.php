<?
/**
 * Short descrition
 *
 * Long description (can contatin many lines)
 *
 * @author You Name <your@email.org>
 * @todo You have something do finish in the future
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 **/

class AMProjectForums extends CMObj {


   public function configure() {
     $this->setTable("ProjectForums");

     $this->addField("codeProject",,"0",1,0,0);
     $this->addField("codeForum",,"0",1,0,0);

     $this->addPrimaryKey("codeProject");
     $this->addPrimaryKey("codeForum");

  }
}

//put your functions here

?>
