<?php

include_once("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("forum");

$pag = new AMTForum;

//tests if the forum code was passed and if
//the forum really exists.
$error = 0;
if(!empty($_REQUEST['frm_codeForum'])) {
     $forum = new AMForum;
     $forum->code = $_REQUEST['frm_codeForum'];
     try{
       $forum->load();
       
       $aco = $forum->getACO();
       if( !( ($aco->testUserPrivilege($_SESSION['user']->codeUser, AMForum::PRIV_ALL)) ||
	      ($aco->testUserPrivilege($_SESSION['user']->codeUser, AMForum::PRIV_VIEW)) ) ) $error = 3;
     }catch(CMDBNoRecord $e){
       //sets an error message;
       $error = 1;
     }
     
}
else {
  $error = 2;
}

//this variable is an array of messages that should be exibed open by the AMTForumRende
$show_open = array();

if($error) {
  switch($error) {
  case 1: $_REQUEST['frm_amerror'] = "forum_not_found"; break;
  case 2: $_REQUEST['frm_amerror'] = "no_id"; break;
  case 3: $_REQUEST['frm_amerror'] = "no_privileges"; break;
  }
  
  $pag->add("<br><div align=center><a href=\"".$_SERVER['HTTP_REFERER']."\" ");
  $pag->add("class=\"cinza\">".$_language['back']."</a></div><br>");
  echo $pag;

  die();
}


if(!empty($_REQUEST['frm_action'])) {
  switch($_REQUEST['frm_action']) {
    //forum actions
  case "A_forum_edit":
        $box = new AMColorBox($title,AMColorBox::COLOR_BOX_BEGE);
    $box->setWidth("500px");
    $box->add("<br/>");

    $box->add("<FORM ACTION='$_SERVER[PHP_SELF]'>");
    $box->add("<INPUT TYPE=hidden NAME=frm_action value='A_forum_save'>");
    $box->add("<INPUT TYPE=hidden NAME=frm_codeForum value='$forum->code'>");
    $box->add("$_language[frm_name] <INPUT TYPE=text NAME=frm_name VALUE='$forum->name'>");

    $box->add("<P style='text-align: right'><BUTTON TYPE=SUBMIT CLASS='image-button'><IMG SRC='$_CMAPP[imlang_url]/bt_criar_forum.gif'></BUTTON>");
    $box->add("</FORM>");

    $pag->add($box);
    echo $pag;
    die();

    break;
  case "A_forum_save":
    $forum->name = $_REQUEST['frm_name'];
    try {
      $forum->save();
      $pag->addMessage($_language['msg_forum_edited']);
    } catch(CMObjException $e) {
      $pag->addMessage($_language['msg_forum_edited']);
    }
    
    break;
    //messages actions
  case "A_post":
    $message = new AMForumMessage;
    $message->loadDataFromRequest();
    $message->body = stripslashes($message->body);
    $message->codeUser = $_SESSION['user']->codeUser;
    $message->timePost = time();

    try {
      $message->save();
      $pag->addMessage($_language['msg_message_posted']);
      $show_open[] = $message->code;
    } catch(CMDBException $e) {
      $pag->addError($_language['error_message_not_posted']);
    }

    break;
  case "A_edit":
    $message = new AMForumMessage;
    $message->code = $_REQUEST["frm_parent"];

    try {
      $message->load();
    } catch(CMDBException $e) {
      $pag->addError($_language['error_message_not_posted']);
    }
    
    $message->body = stripslashes($message->body);
    $message->title = $_REQUEST['frm_title'];

    try {
      $message->save();
      $pag->addMessage($_language['msg_message_posted']);
      $show_open[] = $message->code;
    } catch(CMDBException $e) {
      $pag->addError($_language['error_message_not_posted']);
    }

    break;
  case "A_delete":
    $message = new AMForumMessage;
    $message->code = $_REQUEST["frm_code"];

    try {
      $message->load();
      $message->delete();
    } catch(CMDBException $e) {
      $pag->addError($_language['error_message_not_deleted']);
    }

    break;
  }
}

//create a new instance of the class that render
//the forum
$pag->add("<br><br>");
$pag->add(new AMTForumRender($forum,$show_open));

echo $pag;


?>