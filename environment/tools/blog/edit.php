<?php
/**
 * Edit diary profile
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMDiary
 * @category AMVisualization
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */

include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray('blog');
$_CMAPP['smartform'] = array();
$_CMAPP['smartform']['language'] = $_language;

$pag = new AMTBlog();

if(array_key_exists('action', $_REQUEST)) {
	switch($_REQUEST['action']) {
		case "A_edit":
			$profile = new AMBlogProfile;
			$profile->codeUser = $_SESSION['user']->codeUser;

    //try to load the profile from the DB;
			try{
				$profile->load();
			} catch (CMDBException $exception) {
      //if the loads fail, do nothing. The CMObj will handle the 
      //insertion
			}

			$image = $_SESSION['diary_profile_image'];
			if(($image->state==CMObj::STATE_NEW) ||
			($image->state==CMObj::STATE_DIRTY_NEW)) {
				try {
					$image->save();
				} catch(CMDBException $e) {
					$pag->addError($_language['profile_not_saved']);
				}
			}

			if(($profile->image!=0) and ($profile->image != $image->codeFile)) {
				$old_image = new AMFile;
				$old_image->codeFile = $profile->image;
				try {
					$old_image->load();
					$old_image->delete();
				} catch (CMDBException $exception) {
	//hummm, some problem ocured. I will ignore the problem.
	//so lost image will remain in the dabatase. I will be
	//cool  to log this information for the admin
				}
				
			}

			$profile->titleBlog= $_REQUEST['frm_titleBlog'];
			$profile->text = $_REQUEST['frm_text'];
			$profile->image = $image->codeFile;

			try{
				$profile->save();
			} catch (CMDBException $exception) {
				$pag->addError($_language[profile_not_saved]);
				break;
			}
			Header("Location: $_CMAPP[services_url]/Blog/Blog.php?frm_ammsg=profile_edit_success&frm_type=user");
			die();
			break;

		case "fatal_error":
   //No caso de um erro fatal.
   //A mensagem de erro e exibida pelo proprio template AMMain.
			$pad->add("<div align=center><a href=\"$_SERVER[PHP_SELF]\">$_language[try_again]</a></div>");
			break;


	}
}


$profile = new AMBlogProfile;
$profile->codeUser = $_SESSION['user']->codeUser;

try {
	$profile->load();
} catch (CMDBNoRecord $exception)  {
  //the user dont created a profile yet
}

//dont lose the data when the image is uploaded
if(array_key_exists('frm_titleBlog', $_REQUEST) && !empty($_REQUEST['frm_titleBlog'])) $profile->titleBlog = $_REQUEST['frm_titleBlog'];
if(array_key_exists('frm_text', $_REQUEST) && !empty($_REQUEST['frm_text'])) $profile->text = $_REQUEST['frm_text'];

if(array_key_exists('frm_tituloBlog',$_REQUEST)) {
	$profile->tituloBlog = $_REQUEST['frm_tituloBlog'];
}

$caixa = new AMBoxBlogProfile("","aaa");

//I decided to use an ordinary form insted the AMWSmartform to manipulate easily the
//image submit

$caixa->add("<form name=cad_user method=post action=\"$_SERVER[PHP_SELF]\" enctype=\"multipart/form-data\">");
$caixa->add("<input type=hidden name=action value=\"\">");

//title
$caixa->add("<span class=\"fontgray\">$_language[frm_titleBlog]</span><br>");
$caixa->add("<input name=\"frm_titleBlog\" id=\"frm_titleBlog\" value=\"$profile->titleBlog\" size=\"60\" maxlength=\"60\" type=\"text\">");
$caixa->add("<span class=\"fontgray\">$_language[frm_text]</span><br>");
$caixa->add("<textarea name=\"frm_text\" id=\"frm_text\" size=256 rows=8 cols=40>$profile->text</textarea>");

if(array_key_exists('frm_foto',$_FILES)) {
	$_SESSION['diary_profile_image'] = new AMBlogImage;
	try {
		$_SESSION['diary_profile_image']->loadImageFromRequest("frm_foto");
	}
	catch(AMEImage $e) {
		header("Location:$_SERVER[PHP_SELF]?frm_amerror=invalid_image_type");
	}

	$view = $_SESSION['diary_profile_image']->getView();
	$caixa->add("<p align=center>");
	$caixa->add($view);

	$_SESSION['cad_foto']=serialize($_SESSION['cad_foto']);
}
else {
	if($profile->image) {
		$_SESSION['diary_profile_image'] = new AMVlogImage;
		$_SESSION['diary_profile_image']->codeArquivo = $profile->image;
		try {
			$_SESSION['diary_profile_image']->load();
			$view = $_SESSION['diary_profile_image']->getView();
			$caixa->add("<p align=center>");
			$caixa->add($view);
		} catch (CMDBNoRecord $e) {
			echo $e;
      //hummm, some error on the image. Probably was deleted from the DB.
		}
	}
}

//get the image types that are allowed in this installation of gd+php
$types = AMImage::getValidImageExtensions();

//image
$caixa->add("<p align=center><span class=\"fontGray\">".$_language['frm_image']);
$caixa->add("&nbsp;".$_language['valid_image_types']." ".implode(", ",$types).".</span>");
$caixa->add("<br><input type=file name=frm_foto onChange=\"this.form.submit()\">");

$caixa->add("<p align=right><br><input type=submit onClick=\"this.form['action'].value='A_edit'\" value=\"$_language[send]\">");
$caixa->add("&nbsp;<input name=\"cancelar\" id=\"cancelar\" value=\"$_language[cancel]\" onclick=\"window.location.href = 'blog.php?frm_type='\" type=\"button\">");

$caixa->add("</form>");


// </td>

// <td class="">


// &nbsp;($caixa);


$pag->add($caixa);
echo $pag;
?>