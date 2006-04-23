<?

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
    //$pag->addError($_language['error_cannot_find_user']);
    //echo $pag; 
    echo $_language['error_cannot_find_user'];
    die();
  }
} else {
  $userDiario = $_SESSION['user'];
}   

if(!empty($userDiario)) {
  $profile= new AMDiarioProfile;
  $profile->codeUser = $userDiario->codeUser;

  try {
    $profile->load();
    $title = $profile->tituloDiario;
    $text = $profile->textoProfile;
  } 
  catch(CMDBNoRecord $exception) {
    $title=$_language['titulo_padrao'].' '.$userDiario->name;
  }

  if(empty($_REQUEST['frm_calYear']) || empty($_REQUEST['frm_calMonth'])) {
    $_REQUEST['frm_calMonth'] = date('m',time());
    $_REQUEST['frm_calYear'] = date('Y',time());
  }

  $posts = $userDiario->listDiaryPosts($_REQUEST['frm_calMonth'],$_REQUEST['frm_calYear']);


   //$xml = "<rdf:RDF><channel>\n";
/* /   $xml  = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
//   $xml .= '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">'."\n"; 
*/
   $xml = '   <title>'.html_entity_decode($title)."</title>\n";
   $xml .= '   <link>'.$_CMAPP[services_url].'/diario/diario.php?frm_codeUser='.$userDiario->codeUser."</link>\n";
   $xml .= '   <description>'.html_entity_decode($text)."</description>\n";
   //$xml .= "   <items><rdf:Seq>\n";

   /*foreach($posts as $post) {
      $xml .= '      <rdf:li rdf:resource="'.$_CMAPP[services_url].
              '/diario/diario.php?frm_codePost='.$post->codePost.'#anchor_post_'.$post->codePost.'" />'."\n";
   }*/

   //$xml .= "   </rdf:Seq></items></channel>\n";

   foreach($posts as $post) {
      //$xml .= "   <item rdf:about=\"".$_CMAPP[services_url].'/diario/diario.php?frm_codePost='.$post->codePost.'#anchor_post_'.$post->codePost."\">\n";
      $xml .= "   <item>\n";
      $xml .= '      <title>'.html_entity_decode($post->titulo).'</title>'."\n";
      $xml .= '      <description>'.html_entity_decode($post->texto).'</description>'."\n";
      $xml .= '      <link>'.$_CMAPP[services_url].'/diario/diario.php?frm_codePost='.$post->codePost.'#anchor_post_'.$post->codePost."</link>\n";
      $xml .= '      <pubDate>'.date("h:i ".$_language['date_format'],$post->tempo).'</pubDate>'."\n";
      //$xml .= '      <dc:date>'.date("h:i ".$_language['date_format'],$post->tempo).'</dc:date>'."\n";
      $xml .= "   </item>\n";
   }

//   $xml .= '</rdf:RDF>';
   $xml .= '</channel></rss>';
   print($xml);

} else {
  echo $_language['error_user_not_logged'];
  //$pag->addError($_language['error_user_not_logged']);
}

?>
