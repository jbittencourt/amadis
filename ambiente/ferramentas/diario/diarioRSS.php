<?
/**
 * RSS feeds file
 * This file is provide RSS feeds to a client softwares able to read them.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMDiary
 * @version 1.0
 * @author Daniel M. Basso <daniel@basso.inf.br>
 */
$_CMAPP['notrestricted'] = True;
include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("diary");
$_CMAPP['smartform'] = array();
$_CMAPP['smartform']['language'] = $_language;

header('Content-type: text/xml');
print '<?xml version="1.0" encoding="UTF-8"?>';
print '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/"><channel>'; 


//tests if an user code was submited
//in this case show the diary of this
//user
if(!empty($_REQUEST['frm_codeUser'])) {
  $userDiario = new AMUser;
  $userDiario->codeUser = $_REQUEST['frm_codeUser'];
  try {
    $userDiario->load();
    $default = false;
  } Catch(CMDBNoRecord $e) {
    echo new AMErrorReport($e, "diarioRSS");
    die();
  }
}

if(!empty($userDiario)) {
  $profile= new AMDiarioProfile;
  $profile->codeUser = $userDiario->codeUser;

  try {
    $profile->load();
    $title = $profile->tituloDiario;
    $text = $profile->textoProfile;
  } 
  catch(CMDBNoRecord $e) {
    $title=$_language['titulo_padrao'].' '.$userDiario->name;
  }

  if(empty($_REQUEST['frm_calYear']) || empty($_REQUEST['frm_calMonth'])) {
    $_REQUEST['frm_calMonth'] = date('m',time());
    $_REQUEST['frm_calYear'] = date('Y',time());

  }

  $posts = $userDiario->listDiaryPosts($_REQUEST['frm_calMonth'],$_REQUEST['frm_calYear']);
  
  
  $xml = '   <title>'.html_entity_decode($title)."</title>\n";
  $xml .= '   <link>'.$_CMAPP[services_url].'/diario/diario.php?frm_codeUser='.$userDiario->codeUser."</link>\n";
  $xml .= '   <description>'.html_entity_decode($text)."</description>\n";
  
  foreach($posts as $post) {
    $xml .= "   <item>\n";
    $xml .= '      <title>'.html_entity_decode($post->titulo).'</title>'."\n";
    $text = str_replace("../../media", "$_CMAPP[media_url]", $post->texto);
    $xml .= '      <description>'.html_entity_decode($text).'</description>'."\n";
    $xml .= '      <link>'.$_CMAPP[services_url].'/diario/diario.php?frm_codePost='.$post->codePost.'#anchor_post_'.$post->codePost."</link>\n";
    $xml .= '      <pubDate>'.date("h:i ".$_language['date_format'],$post->tempo).'</pubDate>'."\n";
    $xml .= "   </item>\n";
  }
  
  $xml .= "</channel>\n</rss>";
  print($xml);
  
} else {
  echo $_language['error_user_not_logged'];
  //$pag->addError($_language['error_user_not_logged']);
}

?>
