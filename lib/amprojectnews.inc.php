<?php
/**
 * Short descrition
 *
 * Long description (can contain many lines)
 *
 * @author You Name <your@email.org>
 * @todo You have something to finish in the future
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 **/

class AMProjectNews extends CMObj {


   public function configure() {
     $this->setTable("ProjectNews");

     $this->addField("code",CMObj::TYPE_INTEGER,"20",1,0,1);
     $this->addField("codeProject",CMObj::TYPE_INTEGER,"20",1,0,0);
     $this->addField("codeUser",CMObj::TYPE_INTEGER,"20",1,0,0);
     $this->addField("title",CMObj::TYPE_VARCHAR,"100",1,"",0);
     $this->addField("text",CMObj::TYPE_TEXT,0,1,"",0);
     $this->addField("time",CMObj::TYPE_INTEGER,"11",1,0,0);

     $this->addPrimaryKey("code");
  }

}