<?
/**
 * This page creates a new project.
 *
 * This page is used to create a new project in AMADIS. It
 * has 3 pages: one for the general project data (name, description, etc),
 * other to select areas and a third for the project image selection. The
 * only user that is added in this stage to the project is the logged user.
 * 
 *
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 **/
include("../../config.inc.php");

$_language = $_CMAPP[i18n]->getTranslationArray("community_create");


$pag = new AMTEditCommunity();

/*
 *Load language module
 */

if(!($_SESSION[community] instanceof AMCommunities)){
  $_SESSION[community] = new AMCommunities;
  $_SESSION[community]->code = $_REQUEST['frm_codeCommunity'];
  try{
    $_SESSION[community]->load();
  }catch(AMException $e){}
}

$el["default"] = $_language[pag_0];
$el[pag_1] = $_language[pag_1];
$el[pag_2] = $_language[pag_2];

$ind =  new AMPathIndicator($el);
$ind->setState($_REQUEST[action]);
$pag->setPathIndicator($ind);


//form box to interface
$cadBox = new AMTCommunityCadBox("", AMTCadBox::COMMUNITY_THEME);

switch($_REQUEST[action]) {

 default:

   $conteudo = "<form method=post action=\"$_SERVER[PHP_SELF]\">";
   $conteudo.= "<input type=hidden name=action value=pag_1><br>";
   $conteudo.= "<input type=hidden name=frm_codeCommunity value=$_REQUEST[frm_codeCommunity]>";
   $conteudo.= "<input type=hidden name=frm_artificio value='yes'>";
   $conteudo.= "<table cellpadding='2' cellspacing='1' width='70%'><tbody><tr><td></td></tr><tr><td>";
   $conteudo.= $_language[frm_name];
   $conteudo.= "<br><input name='frm_name' id='frm_name' value='".$_SESSION[community]->name."' size='30' maxlength='30' type='text'>";
   $conteudo.= "</td></tr><tr><td>";
   $conteudo.= $_language[frm_description];
   $conteudo.= "<br><textarea name='frm_description' id='frm_description' rows='5' cols='35'>".$_SESSION[community]->description."</textarea>";
   $conteudo.= "</td></tr><tr><td>";
   $conteudo.= "<br>".$_language['public']."&nbsp;<input name='frm_flagAuth' id='frm_flagAuth' value='ALLOW' checked='checked' type='radio'>";
   $conteudo.= "<br>".$_language['moderate']."&nbsp;<input name='frm_flagAuth' id='frm_flagAuth' value='REQUEST' type='radio'>";
   $conteudo.= "</td></tr><tr><td><table align='right'><tbody><tr><td>";
   $conteudo.= "&nbsp;<input name='cancelar' id='cancelar' value='".$_language['cancel']."' type='button'>";
   $conteudo.= "</td><td>";
   $conteudo.= "&nbsp;<input name='eviar' id='eviar' value='".$_language['send']."' type='submit'>";
   $conteudo.= "</td></tr></tbody></table></td></tr></tbody></table>";
   $conteudo.= "</form>";

   $cadBox->add($conteudo);
   $cadBox->setTitle($_language[general_data]);
   
   break;
      
 case "pag_1":

   if(!empty($_REQUEST['frm_artificio'])){
     //popula os campos num novo obj
     $_SESSION[tempcommunity] = new AMCommunities;
     $_SESSION[tempcommunity]->name = $_REQUEST['frm_name'];
     $_SESSION[tempcommunity]->description = $_REQUEST['frm_description'];
     $_SESSION[tempcommunity]->flagAuth = $_REQUEST['frm_flagAuth'];
     $_SESSION[tempcommunity]->codeGroup = $_SESSION[community]->codeGroup;
   }

   //image stufff
   
   $_SESSION[cad_image] = new AMCommunityImage;
   try {
     if(!empty($_FILES[frm_image])) 
       $_SESSION[cad_image]->loadImageFromRequest("frm_image");
     else{       
       $_SESSION[cad_image]->codeArquivo = $_SESSION[community]->image;
       $_SESSION[cad_image]->load();
     }
   }
   catch(AMEImage $e) {
     header("Location:$_SERVER[PHP_SELF]?action=pag_1&frm_amerror=invalid_image_type");
   }
   $view = $_SESSION[cad_image]->getView();
   $cadBox->add("<p align=center>");
   $cadBox->add($view);
   
   $_SESSION[cad_image]=serialize($_SESSION[cad_image]);
   
   //get the image types that are allowed in this installation of gd+php
   $types = AMImage::getValidImageExtensions();

   $cadBox->add("<form name=cad_user method=post action=\"$_SERVER[PHP_SELF]\" enctype=\"multipart/form-data\">");
   $cadBox->add("<input type=hidden name=action value=pag_1>");
   $cadBox->add("<input type=hidden name=frm_codeCommunity value=$_REQUEST[frm_codeCommunity]>");
   $cadBox->add("<p align=center>".$_language[frm_image]);
   $cadBox->add("&nbsp;".$_language[valid_image_types]." ".implode(", ",$types).".");
   $cadBox->add("<br><input type=file name=frm_image onChange=\"this.form.submit()\">");
   $cadBox->add("<br><input type=submit onClick=\"this.form['action'].value='pag_2'\" value=\"$_language[next]\">");
   $cadBox->add("</form>");



   $cadBox->setTitle($_language[community_pic]);

   break;

 case "pag_2":

   
   if(!empty($_SESSION[cad_image])) {  
     $foto = unserialize($_SESSION[cad_image]);
     $foto->tempo = time();
     try {
       $foto->save();
     }
     catch(CMDBException $e) {
       header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=saving_picture");
     }

     $_SESSION[tempcommunity]->image = $foto->codeArquivo;
   }

   //if arrives here, we havnt changes in the object

   $_SESSION[community]->name = $_SESSION[tempcommunity]->name;
   $_SESSION[community]->description = $_SESSION[tempcommunity]->description;
   $_SESSION[community]->flagAuth = $_SESSION[tempcommunity]->flagAuth;
   $_SESSION[community]->image = $_SESSION[tempcommunity]->image;
   //   $_SESSION[community]->state = 

   //save the community
   try {
     $_SESSION[community]->save();
   }
   catch(CMDBException $e) {
     if(!empty($foto)) {
       $foto->delete();
     }
     header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=saving_user");
   }
   catch(AMException $e) {
     if(!empty($foto)) {
       $foto->delete();
     }
     header("Location:$_SERVER[PHP_SELF]?action=fatal_error&frm_amerror=creating_user_dir");
   }

   $cod = $_SESSION[community]->code;
   unset($_SESSION[community]);
   unset($_SESSION[tempcommunity]);
   unset($_SESSION[cad_image]);

   //if everything was ok, go the page of the project.

   header("Location: $_CMAPP[services_url]/communities/community.php?frm_codeCommunity=$cod&frm_ammsg=community_updated");
   
   break;

 case "fatal_error":
   //No caso de um erro fatal.
   //A mensagem de erro e exibida pelo proprio template AMMain.
   $cadBox->add("<div align=center><a href=\"$_SERVER[PHP_SELF]\">$_language[try_again]</a></div>");
   break;
}
   
$pag->add($cadBox);
echo $pag;

?>