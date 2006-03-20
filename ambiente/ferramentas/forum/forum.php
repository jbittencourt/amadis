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
  }
  
  $pag->add("<br><div align=center><a href=\"".$_SERVER['HTTP_REFERER']."\" ");
  $pag->add("class=\"cinza\">".$_language['back']."</a></div><br>");
  echo $pag;

  die();
}


if(!empty($_REQUEST['frm_action'])) {
  switch($_REQUEST['frm_action']) {
  case "A_post":
    $message = new AMForumMessage;
    $message->loadDataFromRequest();
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
    
    $message->body = $_REQUEST['frm_body'];
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