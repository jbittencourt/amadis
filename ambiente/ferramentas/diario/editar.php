<?

include("../../config.inc.php");

$_language = $_CMAPP[i18n]->getTranslationArray("diary");
$_CMAPP[smartform] = array();
$_CMAPP[smartform][language] = $_language;

$pag = new AMTDiario();

if(!empty($_REQUEST[action])) {
  switch($_REQUEST[action]) {
  case "A_edit":
    $profile = new AMDiarioProfile;
    $profile->codeUser = $_SESSION[user]->codeUser;

    //try to load the profile from the DB;
    try{ 
      $profile->load();
    } catch (CMDBException $exception) {
      //if the loads fail, do nothing. The CMObj will handle the 
      //insertion
    }

    $image = $_SESSION[diary_profile_image];
    if($image->state==CMObj::STATE_NEW) {
      try {
	$image->save();
      } catch(CMDBException $e) {
	$pag->addError($_language[profile_not_saved]);
      }
    }

    if(($profile->image!=0) and ($profile->image != $image->codeArquivo)) {
      $old_image = new AMArquivo;
      $old_image->codeArquivo = $profile->image;
      try {
	$old_image->load();
	$old_image->delete();
      } catch (CMDBException $exception) {
	//hummm, some problem ocured. I will ignore the problem.
	//so lost image will remain in the dabatase. I will be
	//cool  to log this information for the admin
      }
      
    }

    $profile->tituloDiario= $_REQUEST[frm_tituloDiario];
    $profile->textoProfile= $_REQUEST[frm_textoProfile];
    $profile->image = $image->codeArquivo;
      
    try{ 
      $profile->save();
    } catch (CMDBException $exception) {
      $pag->addError($_language[profile_not_saved]);
      break;
    }
    Header("Location: $_CMAPP[services_url]/diario/diario.php?frm_ammsg=profile_edit_success&frm_type=user");
    die();
    break;

 case "fatal_error":
   //No caso de um erro fatal.
   //A mensagem de erro e exibida pelo proprio template AMMain.
   $pad->add("<div align=center><a href=\"$_SERVER[PHP_SELF]\">$_language[try_again]</a></div>");
   break;

    
  }
}


$profile = new AMDiarioProfile;
$profile->codeUser = $_SESSION[user]->codeUser;

try {
  $profile->load();
} catch (CMDBNoRecord $exception)  {
  //the user dont created a profile yet
}


if(!empty($_REQUEST[frm_tituloDiario])) {
  $profile->tituloDiario = $_REQUEST[frm_tituloDiario];
}

$caixa = new AMBoxDiarioProfile("","aaa");

//I decided to use an ordinary form insted the AMWSmartform to manipulate easily the
//image submit

$caixa->add("<form name=cad_user method=post action=\"$_SERVER[PHP_SELF]\" enctype=\"multipart/form-data\">");
$caixa->add("<input type=hidden name=action value=\"\">");

//title
$caixa->add("<span class=\"fontgray\">$_language[frm_tituloDiario]</span><br>");
$caixa->add("<input name=\"frm_tituloDiario\" id=\"frm_tituloDiario\" value=\"$profile->tituloDiario\" size=\"60\" maxlength=\"60\" type=\"text\">");
$caixa->add("<span class=\"fontgray\">$_language[frm_textoProfile]</span><br>");
$caixa->add("<textarea name=\"frm_textoProfile\" id=\"frm_textoProfile\" rows=8 cols=40>$profile->textoProfile</textarea>");

if(!empty($_FILES[frm_foto])) {
  $_SESSION[diary_profile_image] = new AMDiaryImage;
  try {
    $_SESSION[diary_profile_image]->loadImageFromRequest("frm_foto");
  }
  catch(AMEImage $e) {
    header("Location:$_SERVER[PHP_SELF]?frm_amerror=invalid_image_type");
  }

  $view = $_SESSION[diary_profile_image]->getView();
  $caixa->add("<p align=center>");
  $caixa->add($view);

  $_SESSION[cad_foto]=serialize($_SESSION[cad_foto]);
}
else {
  if($profile->image) {
    $_SESSION[diary_profile_image] = new AMDiaryImage;
    $_SESSION[diary_profile_image]->codeArquivo = $profile->image;
    try {
      $_SESSION[diary_profile_image]->load();
      $view = $_SESSION[diary_profile_image]->getView();
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
$caixa->add("<p align=center><span class=\"fontGray\">".$_language[frm_imagem]);
$caixa->add("&nbsp;".$_language[valid_image_types]." ".implode(", ",$types).".</span>");
$caixa->add("<br><input type=file name=frm_foto onChange=\"this.form.submit()\">");

$caixa->add("<p align=right><br><input type=submit onClick=\"this.form['action'].value='A_edit'\" value=\"$_language[send]\">");
$caixa->add("&nbsp;<input name=\"cancelar\" id=\"cancelar\" value=\"$_language[cancel]\" onclick=\"window.location.href = 'diario.php?frm_type='\" type=\"button\">");

$caixa->add("</form>");


// </td>

// <td class="">


// &nbsp;($caixa);


$pag->add($caixa);
echo $pag;

?>