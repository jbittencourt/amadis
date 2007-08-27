<?php
$_CMAPP['notrestricted'] = True;
include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("webfolio");

$pag = new AMTWebfolio;
$box = new AMTwoColsLayout;

if(isset($_REQUEST['action'])) {
  switch($_REQUEST['action']) {
  case "A_make_friend":
    /**
     *Adiciona um amigo
     */    
    try {
      $_SESSION['user']->addFriend($_REQUEST['frm_codeUser'], $_REQUEST['frm_comentary']);
      header("Location:$_SERVER[PHP_SELF]?frm_ammsg=invitation_user_success&&frm_codeUser=$_REQUEST[frm_codeUser]");
    }catch(CMException $e) {
      header("Location:$_SERVER[PHP_SELF]?frm_amerror=invitation_user_failed&&frm_codeUser=$_REQUEST[frm_codeUser]");
    }
    break;
  case "A_make_reject":
    /**
     *Rejeita um amigo
     */
    try {
      $friend = new AMFriend;
      $friend->codeFriend = $_REQUEST['frm_codeUser'];
      $friend->codeUser = $_SESSION['user']->codeUser;
      $friend->comentary = $_REQUEST['frm_comentary'];
      $friend->status = AMFriend::ENUM_STATUS_REJECTED;
      $friend->time = time();
      $friend->save();
      header("Location:$_SERVER[PHP_SELF]?frm_ammsg=invitation_user_success&&frm_codeUser=$_REQUEST[frm_codeUser]");
    }catch(CMException $e) {
      header("Location:$_SERVER[PHP_SELF]?frm_amerror=invitation_user_failed&&frm_codeUser=$_REQUEST[frm_codeUser]");
    }
    break;
  case "A_send_message":
    try {
      $message = new AMUserMessages;
      $message->message = $_REQUEST['frm_message'];
      $message->codeTo = $_REQUEST['frm_codeUser'];
      $message->codeUser = $_SESSION['user']->codeUser;
      $message->time = time();  
      $message->save();
      $pag->addMessage($_language['error_message_send_success']);
    }catch (CMException $e) {
      $pag->addError($_language['error_message_not_send']."<br />".$_language['error_contact_admin'], $e->getMessage());
    }
    break;
  }
}
 
try{
  $user = new AMUser;
  $user->codeUser = $_REQUEST['frm_codeUser'];
  $user->load();
}catch(CMDBException $e) {
  $pag->addError($_language['no_user_found']);
  $pag->add("<a href='$_SERVER[HTTP_REFERER]'>$_language[back]</a>");
  echo $pag;
  die();
}


$foto = AMUserPicture::getImage($user);
if($foto == AMUserPicture::DEFAULT_IMAGE) {
	$box->add(new AMTUserImage(AMUserPicture::DEFAULT_IMAGE, AMImageTemplate::METHOD_DEFAULT), AMTwoColsLayout::LEFT);
} else $box->add(new AMTUserImage($foto),AMTwoColsLayout::LEFT);

$box->add("<p><span class=\"texto\"><b>".$user->name."<br />".date($_language['date_format'],(integer) $user->birthDate)."</b>", AMTwoColsLayout::LEFT);

$box->add('<br /><span class="texto"><br />'.$user->aboutMe.'<br /></span>',
	  AMTwoColsLayout::LEFT);


$box->add('<br /><span class="texto"><b>'.$user->email.'</b></span>',
	  AMTwoColsLayout::LEFT);

$box->add("</font>",
	  AMTwoColsLayout::LEFT);



$box->add(new AMBMyWebfolio, AMTwoColsLayout::RIGHT);
$box->add("<br />", AMTwoColsLayout::RIGHT);

$pag->add($box);

//users projects
$projs = $user->listProjects();
$box2 = new AMColorBox($_CMAPP['imlang_url'].'/box_wfprojetos_tit.gif',AMColorBox::COLOR_BOX_BLUE);
if($projs->__hasItems()) {
  $box2->add(new AMTIconList($projs,AMTIconList::PROJECT_LIST,7));
  $box2->add("<br /><a href='$_CMAPP[services_url]/projects/listprojects.php'>&raquo; $_language[list_all_projects]</a>");
}
else {
  $box2->add("<span class='texto'>$_language[user_no_projects]</span>");
}

$pag->add($box2);


//new communities
$box3 = new AMColorBox("$_CMAPP[imlang_url]/box_wfcomunidades_tit.gif",AMColorBox::COLOR_BOX_YELLOWB);

$comms = $user->listCommunities();
if($comms->__hasItems()) {
  $box3->add(new AMTIconList($comms,AMTIconList::COMMUNITY_LIST,7));
  $box3->add("<br /><a href='$_CMAPP[services_url]/communities/listcommunities.php'>&raquo; $_language[list_all_communities]</a>");
} 
else {
  $box3->add("<span class='texto'>$_language[user_no_communities]</span>");
}

$pag->add($box3);


//friends
$box4 = new AMColorBox("$_CMAPP[imlang_url]/box_wfamigos_tit.gif",AMColorBox::COLOR_BOX_PINK);

$friends = $user->listFriends();
if($friends->__hasItems()) {  
  $box4->add(new AMTIconList($friends,AMTIconList::USER_LIST,7));
  $box4->add("<br /><a href='$_CMAPP[services_url]/webfolio/listfriends.php?frm_codeUser=$user->codeUser'>&raquo; $_language[list_all_friends]</a>");
} 
else {
  $box4->add("<span class='texto'>$_language[user_no_friends]</span>");
}

$pag->add($box4);

//shared files
$box5 = new AMBUserLibrary($user, 5, 1);
$pag->add($box5);


echo $pag;