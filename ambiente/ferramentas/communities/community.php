<?
$_CMAPP[notrestricted] = 1;

include("../../config.inc.php");


$_language = $_CMAPP[i18n]->getTranslationArray("communities");

$pag = new AMTCommunities;
$box = new AMTwoColsLayout;

if(!empty($_REQUEST[frm_codeCommunity])) {
     $community = new AMCommunities;
     $community->code = $_REQUEST[frm_codeCommunity];
     try{
       $community->load();
     }catch(CMDBNoRecord $e){
       $_REQUEST[frm_amerror] = "community_not_exists";
       
       //$pag->add("<br><div align=center><a href=\"".$_SERVER[HTTP_REFERER]."\" ");
       //$pag->add("class=\"cinza\">".$_language[voltar]."</a></div><br>");
       echo $pag;
       die();
     }
} else { 
  $_REQUEST[frm_amerror] = "no_community_id";
  
  $pag->add("<br><div align=center><a href=\"".$_SERVER[HTTP_REFERER]."\" ");
  $pag->add("class=\"cinza\">".$_language[voltar]."</a></div><br>");
  echo $pag;
  die();
}


//checks if the user is a member of the community
if(!empty($_SESSION[user])) {
  $isMember = $community->isMember($_SESSION[user]->codeUser);
}

$_CMAPP[smartform][language] = $_language;

// if($isMember) {
//   $req = new AMBProjectRequest($community);
//   if($req->hasRequests()) { 
//     $req->setWidth($box->getWidth());
//     $pag->add($req);
//   }
// }



/*
 *INICIO DA PAGINA
 */

//coluna da esquerda
$box->add("<font class=\"txttitcomunidade\">$_language[community]:<br> ".$community->name."<br>", AMTwoColsLayout::LEFT);
$box->add("<img src=\"".$_CMAPP[images_url]."/dot.gif\" border=\"0\" height=10 width=1><br>", AMTwoColsLayout::LEFT);

$box->add(new AMTCommunityImage($community->image), AMTwoColsLayout::LEFT);

$box->add("<br>", AMTwoColsLayout::LEFT);
$box->add("<img src=\"".$_CMAPP[images_url]."/dot.gif\" border=\"0\" height=10 width=1><br>", AMTwoColsLayout::LEFT);
$box->add("<font class=\"texto\">$community->description<br>",
	  AMTwoColsLayout::LEFT);
$box->add("<img src=\"".$_CMAPP[images_url]."/dot.gif\" border=\"0\" height=10 width=1><br>", AMTwoColsLayout::LEFT);

$box->add(new AMBCommunitiesNews(AMBCommunitiesNews::COMMUNITY_NEWS), AMTwoColsLayout::LEFT);

/**
 *FINAL DA COLUNA ESQUERDA
 **/

/*
 *COLUNA DIREITA
 */

$box->add("<img src=\"".$_CMAPP[images_url]."/dot.gif\" width=\"20\" height=\"1\" border=\"0\">", AMTwoColsLayout::RIGHT);

if($_SESSION[user] instanceof CMUser) {
  if($community->isAdmin($_SESSION[user]->codeUser)) {
    $box->add(new AMBCommunityItems,AMTwoColsLayout::LEFT);
    $box->add("<br>",AMTwoColsLayout::LEFT);
  }

/*
 *CAIXA DE EDICAO DO PROJETO
 */

  if($community->isAdmin($_SESSION[user]->codeUser)) {
    $communityEdit = new AMBCommunityEdit;
    $box->add($communityEdit,AMTwoColsLayout::RIGHT);
  }
  else {
    //$box->add(new AMBProjectJoin($proj),AMTwoColsLayout::RIGHT);
  }

}

/*
 *MEMBROS DA COMUNIDADE
 */
$box->add("<br>", AMTwoColsLayout::RIGHT);
$box->add(new AMBCommunityMembers, AMTwoColsLayout::RIGHT);

$box->add("<br>", AMTwoColsLayout::RIGHT);
$box->add(new AMBCommunityProjects, AMTwoColsLayout::RIGHT);

/*
 *CADASTRO DE NOTICIAS
 */

// if($isMember) {
//   $projNews = new AMBProjectNews($proj);
//   $box->add($projNews,AMTwoColsLayout::RIGHT);
// }

$pag->add($box);
echo $pag;
?>
