<?
$_CMAPP['notrestricted'] = 1;
include("../../config.inc.php");


$_language = $_CMAPP['i18n']->getTranslationArray("projects");

$pag = new AMTProjeto;
$box = new AMTwoColsLayout;


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


//checks if the user is a member of the project
$isMember = false;
if(!empty($_SESSION['user'])) {
  $isMember = $group->isMember($_SESSION['user']->codeUser);
  if(!$isMember) $proj->hit();
}
$_CMAPP['smartform']['language'] = $_language;

if($isMember) {
  $req = new AMBProjectRequest($proj);
  if($req->hasRequests()) { 
   $pag->add($req);
  }
}




/*
 *INICIO DA PAGINA
 */

//coluna da esquerda
$box->add("<font class=\"project_title\">$_language[project]: ".$proj->title."<br>",
	  AMTwoColsLayout::LEFT);
$box->add("<img src=\"".$_CMAPP['images_url']."/dot.gif\" border=\"0\" height=\"10\" width=\"1\"><br>",
	  AMTwoColsLayout::LEFT);

$box->add(new AMTProjectImage($proj->image),
	  AMTwoColsLayout::LEFT);

$box->add("      <br>",
	  AMTwoColsLayout::LEFT);
$box->add("<img src=\"".$_CMAPP['images_url']."/dot.gif\" border=\"0\" height=\"10\" width=\"1\"><br>",
	  AMTwoColsLayout::LEFT);

$box->add("<font class=\"texto\">$_language[project]: $proj->title <br>$proj->description<br>",
	  AMTwoColsLayout::LEFT);
$box->add("<img src=\"".$_CMAPP['images_url']."/dot.gif\" border=\"0\" height=\"10\" width=\"1\"><br>",
	  AMTwoColsLayout::LEFT);

/*
 *STATUS DO PROJETO
 */
$projStatus = $proj->getStatus();
$box->add("    <img src=\"".$_CMAPP['imlang_url']."/img_dados_projeto.gif\"><br>",
	  AMTwoColsLayout::LEFT);
$box->add("    <font class=\"texto\"><b>$_language[project_in]: $projStatus</b><br>",
	  AMTwoColsLayout::LEFT);
$box->add("    <img src=\"".$_CMAPP['images_url']."/dot.gif\" border=\"0\" height=\"10\" width=\"1\"><br>",
	  AMTwoColsLayout::LEFT);

/*
 *AREAS
 */
$projAreas = $proj->listAreas();
$box->add("<b>$_language[project_areas]</b>",AMTwoColsLayout::LEFT);
if(!empty($projAreas->items)) {
  foreach($projAreas as $item) {
    $box->add($item->nomArea.",",AMTwoColsLayout::LEFT);
  }
  $box->add("<br><br>",AMTwoColsLayout::LEFT);
}
else {
  $box->add($_language['any_area']."<br><br>",AMTwoColsLayout::LEFT);
}

/*
 *EQUIPE DO PROJETO
 */
$projMembers = $group->listActiveMembers();
$orfan = false;
$box->add("    <b>$_language[project_group]</b></font><br>",AMTwoColsLayout::LEFT);

if($projMembers->__hasItems()) {
  foreach($projMembers as $item) {
    $temp = new AMTUserInfo($item);
    $temp->setClass("text");
    $box->add($temp,AMTwoColsLayout::LEFT);
    $box->add("<br>",AMTwoColsLayout::LEFT);
  }
}
else { 
  $orfan = true;
  $adopt = new AMBProjectOrfan($proj);
  $adopt->setWidth($box->getWidth());
  $pag->add($adopt);
  $box->add($_language['no_members']."<br>",AMTwoColsLayout::LEFT);
}

$box->add("<a href=\"".$_CMAPP['services_url']."/projetos/members.php?frm_codProjeto=".$proj->codeProject."\"",AMTwoColsLayout::LEFT);
$box->add(" class=\"green\">$_language[more_members]</a><br><br>",AMTwoColsLayout::LEFT);
$box->add("<br>",AMTwoColsLayout::LEFT);
// END of the members box.



$projItens = new AMBProjectItems;
$box->add($projItens,AMTwoColsLayout::LEFT);


/**
 *FINAL DA COLUNA ESQUERDA
 **/
$box->add("<img src=\"".$_CMAPP['images_url']."/dot.gif\" width=\"20\" height=\"1\" border=\"0\">",
	  AMTwoColsLayout::RIGHT);

/*
 *COLUNA DIREITA
 */
/*
 *CAIXA DE EDICAO DO PROJETO
 */

if(!$orfan) {
  if($_SESSION['user'] instanceof CMUser) {
    if($isMember) {
      $projEdit = new AMBProjectEdit($proj);
      $box->add($projEdit,AMTwoColsLayout::RIGHT);
    }
    else {
      $box->add(new AMBProjectJoin($proj),AMTwoColsLayout::RIGHT);
    }
    $box->add("<br>",AMTwoColsLayout::RIGHT);
  }
}

/*
 * CAIXA DA BIBLIOTECA DO PROJETO
 */
 

if($_SESSION['user'] instanceof CMUser) {
  if($isMember) {
    $checkIfExist = new AMProjectLibraryEntry($proj->codeProject);
    $checkIfExist->libraryExist();
    $projLibrary = new AMBProjLibrary($proj);
    $box->add($projLibrary,AMTwoColsLayout::RIGHT);
  }
  $box->add("<br>",AMTwoColsLayout::RIGHT);  
}

// fim lib projeto
 
$projComents = new AMBProjectComents;
$box->add($projComents,AMTwoColsLayout::RIGHT);

$box->add("<br>",AMTwoColsLayout::RIGHT);

/*
 *CADASTRO DE NOTICIAS
 */

$projNews = new AMBProjectNews($proj);
$box->add($projNews,AMTwoColsLayout::RIGHT);

$pag->add($box);
echo $pag;
?>
