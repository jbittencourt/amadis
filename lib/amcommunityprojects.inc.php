<?php
/**
 * Model an invitation or request to be member of a project.
 *
 * There is three ways to become member of a project: the first one is to
 * create the project, the other is to request it and the last is to be
 * invited by some project member. This class models the last two ones.
 * To do this there is two special properties. The type propertie tell
 * if an record is an invitation, done by a project member, or a request,
 * done by the user. The status field, say if the invitation/request has
 * alredy been answered (REJECTED,ACCPETED) or not (NOT_ANSWERED).
 *
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 **/

class AMCommunityProjects extends CMObj {


   public function configure() {
     $this->setTable("CommunityProjects");

     $this->addField("codeCommunity",CMObj::TYPE_INTEGER,"20",1,0,0);
     $this->addField("codeProject",CMObj::TYPE_INTEGER,"20",1,0,0);

     $this->addPrimaryKey("codeProject");
     $this->addPrimaryKey("codeCommunity");

  }
}