<?php
$_CMAPP['notrestricted'] = 1;

include("../../config.inc.php");


$_language = $_CMAPP['i18n']->getTranslationArray("communities");
$pag = new AMTCommunities;
$box = new AMTwoColsLayout;


if(!empty($_REQUEST['frm_codeCommunity'])) {
  	$community = new AMCommunities;
  	$community->code = $_REQUEST['frm_codeCommunity'];
  	try{
		$community->load();
    	$group = $community->getGroup();
  	}catch(CMDBNoRecord $e){
    	$_REQUEST['frm_amerror'] = "community_not_exists";    
    	echo $pag;
    	die();
  	}catch(CMObjEPropertieValueNotValid $er){    
    	$_REQUEST['frm_amerror'] = "community_not_exists";    
    	echo $pag;
    	die(); 
  	}
} else { 
  	$_REQUEST['frm_amerror'] = "no_community_id";
  
  	$pag->add("<br /><div align=center><a href=\"".$_SERVER['HTTP_REFERER']."\" ");
  	$pag->add("class=\"cinza\">".$_language['voltar']."</a></div><br />");
  	echo $pag;
  	die();
}


//checks if the user is a member of the community
if(!empty($_SESSION['user'])) {
  	$isMember = $group->isMember($_SESSION['user']->codeUser);
}

$_CMAPP['smartform']['language'] = $_language;

if($isMember) {
  	$req = new AMBCommunityRequest($community);
  	if($req->hasRequests()) { 
    	$req->setWidth($box->getWidth());
    	$pag->add($req);
  	}
}



/*
 *INICIO DA PAGINA
 */

//coluna da esquerda
$box->add("<span class=\"txttitcomunidade\">$_language[community]: ".$community->name."<br /></span>", AMTwoColsLayout::LEFT);
$box->add('<img src="'.$_CMAPP['images_url'].'/dot.gif" height="10" width="1" alt="" /><br />', AMTwoColsLayout::LEFT);

if($community->image == 0) $box->add(new AMTCommunityImage(AMCommunityImage::DEFAULT_IMAGE , AMTCommunityImage::METHOD_DEFAULT), AMTwoColsLayout::LEFT);
else $box->add(new AMTCommunityImage($community->image), AMTwoColsLayout::LEFT);

$box->add("<br />", AMTwoColsLayout::LEFT);
$box->add('<img src="'.$_CMAPP['images_url'].'/dot.gif" height="10" width="1" alt="" /><br />', AMTwoColsLayout::LEFT);
$box->add("<span class=\"texto\">$community->description<br /></span>",
	  AMTwoColsLayout::LEFT);
$box->add('<img src="'.$_CMAPP['images_url'].'/dot.gif" height="10" width="1" alt="" /><br />', AMTwoColsLayout::LEFT);

$box->add("<br />", AMTwoColsLayout::LEFT);
$box->add(new AMBCommunityMembers($community), AMTwoColsLayout::LEFT);

/*
 *CADASTRO DE NOTICIAS
 */

$communityNews = new AMBCommunitiesNews(AMBCommunitiesNews::COMMUNITY_NEWS);
$box->add($communityNews,AMTwoColsLayout::LEFT);

/**
 *FINAL DA COLUNA ESQUERDA
 **/

/*
 *COLUNA DIREITA
 */

$box->add("<img src=\"".$_CMAPP['images_url']."/dot.gif\" width=\"20\" height=\"1\" alt=\"\" />", AMTwoColsLayout::RIGHT);

$box->add(new AMBCommunityItems,AMTwoColsLayout::RIGHT);
$box->add("<br />",AMTwoColsLayout::RIGHT);
if($_SESSION['user'] instanceof CMUser) {
  
  /*
   *CAIXA DE EDICAO DO PROJETO
   */
  if($isMember){    
    $box->add(new AMBCommunityEdit($community),AMTwoColsLayout::RIGHT);    
  }  
  
  if(!$group->isMember($_SESSION['user']->codeUser)) {
    $box->add(new AMBCommunityJoin($community),AMTwoColsLayout::RIGHT);
  }
  
}

/*
 *RELATED PROJECTS
 */

$box->add("<br />", AMTwoColsLayout::RIGHT);
$box->add(new AMBCommunityProjects, AMTwoColsLayout::RIGHT);

$pag->add($box);
echo $pag;