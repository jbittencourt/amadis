<?
$_CMAPP['notrestricted'] = True;
include("../../config.inc.php");
include('lastRSS.php');

$_language = $_CMAPP['i18n']->getTranslationArray("projects");
//$_language = $_CMAPP['i18n']->getTranslationArray("diary");
$_CMAPP['smartform'] = array();
$_CMAPP['smartform']['language'] = $_language;

$pag = new AMTAgregador();

//checks if the user is a member of the project
if(isset($_REQUEST['frm_codProjeto']) && !empty($_REQUEST['frm_codProjeto'])) {
  $proj = new AMProjeto;
  $proj->codeProject = $_REQUEST['frm_codProjeto'];
  try{
    $proj->load();
    $group = $proj->getGroup();
  }catch(CMDBNoRecord $e){
    $location  = $_CMAPP[services_url]."/projetos/projeto.php?frm_amerror=project_not_exists";
    $location .= "&frm_codProjeto=".$_REQUEST[frm_codProjeto];
    header("Location:$location");
  }
} else {
  $_REQUEST['frm_amerror'] = "any_project_id";

  $pag->add("<br><div align=center><a href=\"".$_SERVER['HTTP_REFERER']."\" ");
  $pag->add("class=\"cinza\">".$_language['back']."</a></div><br>");
  echo $pag;
  die();
}

$isMember = false;
if(!empty($_SESSION['user'])) {
  $isMember = $group->isMember($_SESSION['user']->codeUser);
  if(!$isMember) $proj->hit();
}
//$_CMAPP['smartform']['language'] = $_language;

// $default = true;

// //tests if an user code was submited
// //in this case show the diary of this
// //user
// if(!empty($_REQUEST['frm_codeUser'])) {
//   $userDiario = new AMUser;
//   $userDiario->codeUser = $_REQUEST['frm_codeUser'];
//   try {
//     $userDiario->load();
//     $default = false;
//   } Catch(CMDBNoRecord $e) {
//     $pag->addError($_language['error_cannot_find_user']);
//     echo $pag; 
//     die();
//   }
// }
// else {
//   //if no user code was submited, test for
//   //a message code, so we can load the
//   //data for the query
//   if(!(empty($_REQUEST['frm_codePost']))) {
//     $post = new AMDiarioPost;
//     $post->codePost = $_REQUEST['frm_codePost'];
//     try {
//       $post->load();
//       $userDiario = new AMUser;
//       $userDiario->codeUser = $post->codeUser;
//       $userDiario->load();
//       $default = false;
//     } Catch(CMDBNoRecord $e) {
//       $pag->addError($_language['error_post_does_not_exist']);
//       echo $pag;
//       die();
//     }
    
//     $_REQUEST['frm_calMonth'] = date("m",$post->tempo);
//     $_REQUEST['frm_calYear'] = date("Y",$post->tempo);  
//   }

// }

// //If there is codePost or codeUser submited, or there
// //was some error in the load, the defautl behavior is
// //to load the diary of the user.
// if($default) {
//   $userDiario = $_SESSION['user'];
// }   

// if(!empty($userDiario)) {
//   $profile= new AMDiarioProfile;
//   $profile->codeUser = $userDiario->codeUser;

//   try {
//     $profile->load();
//     $title = $profile->tituloDiario;
//     $text = $profile->textoProfile;
//     $linkEditar = "editar.php?frm_action=editar";
//   } 
//   catch(CMDBNoRecord $exception) {
//     $title=$_language['titulo_padrao'].' '.$userDiario->name;
//     $linkEditar = "editar.php";
//   }

//   if(empty($_REQUEST['frm_calYear']) || empty($_REQUEST['frm_calMonth'])) {
//     $_REQUEST['frm_calMonth'] = date('m',time());
//     $_REQUEST['frm_calYear'] = date('Y',time());
//   }

//   $posts = $userDiario->listDiaryPosts($_REQUEST['frm_calMonth'],$_REQUEST['frm_calYear']);


//   if($profile->image==0) {
//     $image = new AMTFotoDiario($userDiario->foto); 
//   }
//   else {
//     $image = new AMTFotoDiario($profile->image);
//   }


//   $rsslink="diarioRSS.php?frm_codeUser=".$userDiario->codeUser;
     
//   $caixa = new AMBoxDiario($posts,$userDiario->codeUser,$title,$image,$text,$rsslink);
//   $caixa->setDate($_REQUEST['frm_calMonth'],$_REQUEST['frm_calYear']);
//   if(!empty($_SESSION['user'])) {
//     if($userDiario->codeUser==$_SESSION['user']->codeUser) {
//       $caixa->addCabecalho("<br> <a class=\"diary_header\" href=\"postar.php\" > &raquo; $_language[escrever_nota] </a>");
//       $caixa->addCabecalho("<br> <a class=\"diary_header\" href=\"$linkEditar\" > &raquo; $_language[editar_diario] </a>");
//     }
//   }

//   $pag->setRSSFeed($rsslink,$title);

//   $pag->add($caixa);
// } else {
//   $pag->addError($_language['error_user_not_logged']);
// }

if(isset($_REQUEST['frm_codProjeto']) && !empty($_REQUEST['frm_codProjeto'])) {
  $proj = new AMProjeto;
  $proj->codeProject = $_REQUEST['frm_codProjeto'];
  try{
    $proj->load();
    $group = $proj->getGroup();
  }catch(CMDBNoRecord $e){
    $location  = $_CMAPP[services_url]."/projetos/projeto.php?frm_amerror=project_not_exists";
    $location .= "&frm_codProjeto=".$_REQUEST[frm_codProjeto];
    header("Location:$location");
  }
} else {
  $_REQUEST['frm_amerror'] = "any_project_id";

  $pag->add("<br><div align=center><a href=\"".$_SERVER['HTTP_REFERER']."\" ");
  $pag->add("class=\"cinza\">".$_language['back']."</a></div><br>");
  echo $pag;
  die();
}

$pag->add("<font class=\"project_title\">$_language[project]: ".$proj->title."</font><br/><br/>");
$pag->add("<table><tr><td>");
$pag->add(new AMTProjectImage($proj->image));
$pag->add("</td>");
if ($isMember) {
  $pag->add("<td><span style=\"padding-left: 30px;\">");
  $pag->add("<a href=\"$urledit\" class =\"green\">&raquo; Editar lista de blogs</a></span></td>");
}
$pag->add("</tr></table><br/>");



// load some RSS file
$rss = new lastRSS; 

$q = new CMQuery('AMProjectBlogs');
$q->setFilter("AMProjectBlogs::codeProject=".$_REQUEST['frm_codProjeto']);
$blogs=$q->execute();

foreach($blogs as $blog) {

  //  if ($rs = $rss->get('http://www.mono-project.com/news/index.rss2')) {
  if ($rs = $rss->get($blog->address)) {
    $caixa = new AMBoxAgregador($rs,$userDiario->codeUser,"xawaskaaaaaa",0);
    $pag->add($caixa);
  } else {
    $pag->add('Error: RSS file "'.$blog->address.'" not found...');
  }

}
/*
if ($rs = $rss->get('http://gnomedesktop.org/node/feed')) {
  //if ($rs = $rss->get('http://www.freshfolder.com/rss.php')) {
//  if ($rs = $rss->get('http://lothlorien.lec.ufrgs.br/~dmbasso/ferramentas/diario/diarioRSS.php?frm_codeUser=103')) {
  $caixa = new AMBoxAgregador($rs,$userDiario->codeUser,"xawaskaaaaaa",0);
  $pag->add($caixa);
} else {
  $pag->add('Error: RSS file not found...');
}

// http://lothlorien.lec.ufrgs.br/~dmbasso/ferramentas/diario/diarioRSS.php?frm_codeUser=95
if ($rs = $rss->get('http://lothlorien.lec.ufrgs.br/~dmbasso/ferramentas/diario/diarioRSS.php?frm_codeUser=103')) {
  $caixa = new AMBoxAgregador($rs,$userDiario->codeUser,"xawaskaaaaaa",0);
  $pag->add($caixa);
} else {
  $pag->add('Error: RSS file not found...');
}

*/
echo $pag; 

?>
