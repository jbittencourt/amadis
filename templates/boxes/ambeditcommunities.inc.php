<?
 
class AMBEditCommunities extends AMColorBox {
  
  public function __construct() {
    global $_CMAPP, $_language;
    parent::__construct($_language['edit_communities'],self::COLOR_BOX_BLUA);
  }

 
  public function __toString() {
    
    global $_CMAPP,$_language;
    /*    
     *Buffering html of the box to output screen
     */
    
    switch($_REQUEST['action']) {
    case "A_auth":
      if($_REQUEST[subaction] == "A_authorize" && !empty($_REQUEST[frm_codeCommunity])){
	$c = new AMCommunities;
	$c->code = $_REQUEST[frm_codeCommunity];
	try{
	  $c->load();
	  $c->status = AMCommunities::ENUM_STATUS_AUTHORIZED;
	  $c->save();
	}catch(AMException $e){
	  header("Location:$_SERVER[PHP_SELF]?action=A_auth&frm_amerror=c_auth_failed");
	}
	header("Location:$_SERVER[PHP_SELF]?action=A_auth&frm_ammsg=c_auth_successful");
      }    
      $q = new CMQuery(AMCommunities);
      $q->setFilter("status = '".AMCommunities::ENUM_STATUS_NOT_AUTHORIZED."' ORDER BY time ASC");
      $result = $q->execute();
  
      if($result->__hasItems()){      
	foreach($result as $item)
	  parent::add("<br>$item->name - <a href='".$_SERVER['PHP_SELF']."?action=A_auth&subaction=A_authorize&frm_codeCommunity=".$item->code."'>".$_language['authorize_community']."</a>");     
      }
      else{
	parent::add($_language['all_communities_are_auth']."<br><a href='".$_CMAPP[services_url]."/admin/admin.php'>".$_language[back]."</a>");
      }
      break;
    case "A_delete":
      if($_REQUEST[subaction] == "A_del" && !empty($_REQUEST[frm_codeCommunity])){
	try{
	  //load community
	  $c = new AMCommunities;
	  $c->code = $_REQUEST[frm_codeCommunity];
	  $c->load();
	  //load group
	  $g = new CMGroup;
	  $g->codeGroup = $c->getGroup();
	  $g->load();
	  //load & delete group members
	  $kueri = new CMQuery(CMGroupMember);
	  $kueri->setFilter("codeGroup = ".$c->getGroup);
	  $res = $kueri->execute();
	  if($res->__hasItems())
	    foreach($res as $item)
	      $item->delete();
	  //delete group & community
	  $g->delete();
	  $c->delete();
	}catch(AMException $e){ 
	  header("Location:$_SERVER[PHP_SELF]?action=A_delete&frm_amerror=c_delete_failed");
	}
	//if its ok set msg
	 header("Location:$_SERVER[PHP_SELF]?action=A_delete&frm_ammsg=c_delete_success");
      }

      $q = new CMQuery(AMCommunities);
      $q->setFilter("status = '".AMCommunities::ENUM_STATUS_AUTHORIZED."'");
      $result = $q->execute();
  
      if($result->__hasItems()){      
	foreach($result as $item)
	  parent::add("<br>$item->name - <a href='".$_SERVER['PHP_SELF']."?action=A_delete&subaction=A_del&frm_codeCommunity=".$item->code."'>".$_language['delete']."</a>");     
      }
      else
	parent::add($_language['all_communities_are_del']."<br><a href='".$_CMAPP[services_url]."/admin/admin.php'>".$_language[back]."</a>");      
      
      break;
    }
    
    
      

    
    return parent::__toString();
      
  }
}

?>
