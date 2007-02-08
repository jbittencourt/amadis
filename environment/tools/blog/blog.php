<?php
/**
 * Visualization of diary
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMDiary
 * @category AMVisualization
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMBlogPost, AMBlogComentario
 */

$_CMAPP['notrestricted'] = True;
include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("blog");
$_CMAPP['smartform'] = array();
$_CMAPP['smartform']['language'] = $_language;

$pag = new AMTBlog;
$pag->addXOADHandler('AMBlog', 'AMBlog');

if(!empty($_REQUEST['frm_action'])) {
	switch($_REQUEST['frm_action']) {
		case "A_comentario":
			$comentario = new AMBlogComment;
			$comentario->loadDataFromRequest(); // pega os dados do request e manda p/ o banco
			$comentario->time = time();
			$comentario->codeUser = $_SESSION['user']->codeUser;

			try {
				$comentario->save();
				$pag->addMessage($_language['msg_comments_saved']); //aviso em java script
			}
			catch(CMDBQueryError $erro) {
				$_REQUEST['frm_amerror'] = "comment_not_saved";
			}
				
			break;

			// ------------  teste para deletar posts
		case "A_delete":
			$deletar = new AMBlogPost;
			$deletar->codePost = $_REQUEST['frm_codePost'];
			try {
				$deletar->load();
				if($deletar->countComments()>0){
					$pag->addError($_language['error_post_cannot_be_deleted']);
				}
				else{
					try{
						$deletar->delete();
						header('Location: '.$_CMAPP['services_url'].'/blog/blog.php');
					}catch(CMObjException $exception) {
						$pag->addError($_language['error_post_not_delete']);
					}
				}
			}
				
			catch(CMObjException $exception) {
				$pag->addError($_language['post_not_delete']);

			}
			break;
			// ------------  teste para deletar posts

	}
}

$default = true;

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
		$pag->addError($_language['error_cannot_find_user']);
		echo $pag;
		die();
	}
}
else {
	//if no user code was submited, test for
	//a message code, so we can load the
	//data for the query
	if(!empty($_REQUEST['frm_codePost'])) {
		$post = new AMBlogPost;
		$post->codePost = $_REQUEST['frm_codePost'];
		try {
			$post->load();
			$userBlog = new AMUser;
			$userBlog->codeUser = $post->codeUser;
			$userBlog->load();
			$default = false;
		} Catch(CMDBNoRecord $e) {
			$pag->addError($_language['error_post_does_not_exist'], $e);
			echo $pag;
			die();
		}

		$_REQUEST['frm_calMonth'] = date("m",$post->tempo);
		$_REQUEST['frm_calYear'] = date("Y",$post->tempo);

	}

}

//If there is codePost or codeUser submited, or there
//was some error in the load, the default behavior is
//to load the diary of the user.
if($default) {	
	$userBlog = $_SESSION['user'];
}

if(!empty($userBlog)) {	
	$profile= new AMBlogProfile;
	$profile->codeUser = $userBlog->codeUser;

	$title = "";
	$text = "";

	try {
		$profile->load();
		$title = $profile->titleBlog;
		$text = $profile->text;
		$linkEditar = "edit.php?frm_action=editar";
	}
	catch(CMDBNoRecord $exception) {
		$title = $_language['default_title'].' '.$userBlog->name;
		$linkEditar = "edit.php";
	}


	if(empty($_REQUEST['frm_calYear']) || empty($_REQUEST['frm_calMonth'])) {
		$_REQUEST['frm_calMonth'] = date('m',time());
		$_REQUEST['frm_calYear'] = date('Y',time());
	}

	$posts = $userBlog->listBlogPosts($_REQUEST['frm_calMonth'],$_REQUEST['frm_calYear']);
	
	if($profile->image==0) {
		$image = new AMTBlogImage(AMUserPicture::getImage($userBlog));
	}
	else {
		$image = new AMTBlogImage($profile->image);
	}

	$caixa = new AMBoxBlog($posts,$userBlog->codeUser,$title,$image,$text);
	$caixa->setDate($_REQUEST['frm_calMonth'],$_REQUEST['frm_calYear']);
	$date = getdate(time());
	
	if(!empty($_SESSION['user']) && ($_REQUEST['frm_calMonth']==$date['mon']) && ($_REQUEST['frm_calYear']==$date['year'])) {
		if($userBlog->codeUser==$_SESSION['user']->codeUser) {
			$caixa->addCabecalho("<br> <a class='diary_header' href='post.php' > &raquo; $_language[post_blog] </a>");
			$caixa->addCabecalho("<br> <a class='diary_header' href='$linkEditar' > &raquo; $_language[edit_blog] </a>");
		}
	}
	$rsslink = $_CMAPP['services_url'] . "/blog/blogRSS.php?frm_codeUser=$userBlog->codeUser";
	$pag->setRSSFeed($rsslink,$title);
	$pag->add($caixa);

} else {
	$pag->addError($_language['error_user_not_logged']);
}

echo $pag;