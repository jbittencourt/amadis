<?
/**
 * Short descrition
 *
 * Long description (can contain many lines)
 *
 * @author You Name <your@email.org>
 * @todo You have something to finish in the future
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 **/

class AMLogUploadFiles extends CMObj {

   const ENUM_UPLOADTYPE_PROJECT = "PROJECT";
   const ENUM_UPLOADTYPE_USER = "USER";

   public function configure() {
     $this->setTable("LogUploadFiles");

     $this->addField("uploadType",CMObj::TYPE_ENUM,"",1,self::ENUM_UPLOADTYPE_USER,0);
     $this->addField("codeAnchor",CMObj::TYPE_INTEGER,"11",1,0,0);
     $this->addField("time",CMObj::TYPE_INTEGER,"20",1,0,0);

     $this->addPrimaryKey("uploadType");
     $this->addPrimaryKey("codeAnchor");

     $this->setEnumValidValues("uploadType",array(self::ENUM_UPLOADTYPE_PROJECT,
                                                  self::ENUM_UPLOADTYPE_USER));
  }
  static public function getLastModifieds() {
    $q = new CMQuery('AMLogUploadFiles');
    
    $j = new CMJoin(CMJoin::INNER);
    $j->setClass('AMUser');
    $j->on("AMLogUploadFiles::codeAnchor = AMUser::codeUser");
    
    $q->addJoin($j, "user");
    $q->setOrder("AMLogUploadFiles::time DESC");
    $q->setFilter("AMLogUploadFiles::uploadType = '".AMLogUploadFiles::ENUM_UPLOADTYPE_USER."'");
    $q->setLimit(0,5);
    

    $q2 = new CMQuery('AMLogUploadFiles');
    
    $j = new CMJoin(CMJoin::INNER);
    $j->setClass('AMProjeto');
    $j->on("AMLogUploadFiles::codeAnchor = AMProjeto::codeProject");
    
    $q2->addJoin($j, "project");
    $q2->setOrder("AMLogUploadFiles::time DESC");
    $q2->setFilter("AMLogUploadFiles::uploadType = '".AMLogUploadFiles::ENUM_UPLOADTYPE_PROJECT."'");
    $q2->setLimit(0,5);

    return array("users"=>$q->execute(), "projects"=>$q2->execute());
  }
}


?>