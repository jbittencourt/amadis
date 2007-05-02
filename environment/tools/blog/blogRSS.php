<?php
/**
 * RSS feeds file
 * This file is provide RSS feeds to a client softwares able to read them.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMDiary
 * @version 1.0.2
 * @author Robson Mendonça <robson@lec.ufrgs.br>
 * 
 */
$_CMAPP['notrestricted'] = True;
include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("blog");
$_CMAPP['smartform'] = array();
$_CMAPP['smartform']['language'] = $_language;

header('Content-type: text/xml');
print '<?xml version="1.0" encoding="UTF-8"?>';
print '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/"><channel>'; 


//tests if an user code was submited
//in this case show the diary of this
//user
if(!empty($_REQUEST['frm_codeUser'])) {
  $userBlog = new AMUser;
  $userBlog->codeUser = $_REQUEST['frm_codeUser'];
  try {
    $userBlog->load();
    $default = false;
  } Catch(CMDBNoRecord $e) {
    echo new AMErrorReport($e, "blogRSS");
    die();
  }
}

if(!empty($userBlog)) {
  $profile= new AMBlogProfile;
  $profile->codeUser = $userBlog->codeUser;

  try {
    $profile->load();
    $title = $profile->titleBlog;
    $text = $profile->text;
  } 
  catch(CMDBNoRecord $e) {
    $title=$_language['titulo_padrao'].' '.$userBlog->name;
  }

  if(empty($_REQUEST['frm_calYear']) || empty($_REQUEST['frm_calMonth'])) {
    $_REQUEST['frm_calMonth'] = date('m',time());
    $_REQUEST['frm_calYear'] = date('Y',time());

  }

  $posts = $userBlog->listBlogPosts($_REQUEST['frm_calMonth'],$_REQUEST['frm_calYear']);
  
  
  $xml = '   <title>'.html_entity_decode($title)."</title>\n";
  $xml .= '   <link>'.$_CMAPP[services_url].'/blog/blog.php?frm_codeUser='.$userBlog->codeUser."</link>\n";
  $xml .= '   <description>'.html_entity_decode($text)."</description>\n";
  
  if($posts->__isEmpty()) {
  	$xml .= '<item></item>';
  } else {
  	foreach($posts as $post) {
	    $xml .= "   <item>\n";
    	$xml .= '      <title>'.html_entity_decode($post->title).'</title>'."\n";
    	$text = str_replace("../../media", "$_CMAPP[media_url]", $post->body);
    	$xml .= '      <description>'.html_entity_decode($text).'</description>'."\n";
    	$xml .= '      <link>'.$_CMAPP[services_url].'/blog/blog.php?frm_codePost='.$post->codePost.'#anchor_post_'.$post->codePost."</link>\n";
    	$xml .= '      <pubDate>'.date("h:i ".$_language['date_format'],$post->time).'</pubDate>'."\n";
    	$xml .= "   </item>\n";
  	}
  }
  
  $xml .= "</channel>\n</rss>";
  print($xml);
  
} else {
  echo $_language['error_user_not_logged'];
  //$pag->addError($_language['error_user_not_logged']);
}