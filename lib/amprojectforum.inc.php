<?
/**
 * Class that associates a Forum with a Project.
 *
 * Class that associates a Forum with a Project.
 *
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 **/


class AMProjectForum extends CMObj {


   public function configure() {
     $this->setTable("ProjectForums");

     $this->addField("codeProject",CMObj::TYPE_VARCHAR,20,1,0,0);
     $this->addField("codeForum",CMObj::TYPE_VARCHAR,20,1,0,0);

     $this->addPrimaryKey("codeProject");
     $this->addPrimaryKey("codeForum");

  }
}

//put your functions here

?>
