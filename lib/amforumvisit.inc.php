<?php
/**
 * Class that implements the ForumVisits Table
 *
 * The AMForumVisit class represents an visit of an user to a forum.
 * AMADIS. The discussion forum is a tool where users can send
 * messages in an assyncronous way, of a certain topic.
 *
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 **/

class AMForumVisit extends CMObj {


   public function configure() {
     $this->setTable("ForumVisits");

     $this->addField("codeUser",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("codeForum",CMObj::TYPE_INTEGER,20,1,0,0);
     $this->addField("time",CMObj::TYPE_INTEGER,20,1,0,0);

  }

}