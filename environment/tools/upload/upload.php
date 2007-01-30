<?php
/**
 * Main file to management of the files hosteds in AMADIS
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMUpload
 * @category AMVisualization
 * @version 0.1
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 * @see AMUpload, AMBUpload
 */

include("../../config.inc.php");
include("ambupload.inc.php");
include("ambuploadfloatingbox.inc.php");
include("amtupload.inc.php");
include("amupload.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("upload");

if(!isset($_REQUEST['frm_dirName'])) $_REQUEST['frm_dirName']='';
if(!isset($_language['error_diretory_created_success'])) $_language['error_diretory_created_success']='';

$_language['error_diretory_created_success'] = str_replace("{DIR}","<u>".$_REQUEST['frm_dirName']."</u>",
							   $_language['error_diretory_created_success']);

$urlBase  = $_CMAPP['services_url']."/upload/upload.php?frm_upload_type=".$_REQUEST['frm_upload_type'];
$pathBase = (string) $_conf->app[0]->paths[0]->pages;

switch($_REQUEST['frm_upload_type']) {

 case "project":
   
   $pag = new AMTUpload("top_projetos.gif");
 
   $urlBase .= "&frm_codeProjeto=$_REQUEST[frm_codeProjeto]";
   $upload_type = "name=frm_codeProjeto value=$_REQUEST[frm_codeProjeto]";

   try {
     $proj = new AMProject;
     $proj->codeProject = $_REQUEST['frm_codeProjeto'];
     $proj->load();

     $pag->setTitle($proj->title);
     $thumb = new AMUploadThumb;
     $thumb->codeFile = ($proj->image==0 ? 2: $proj->image);
     $thumb->load();
     $pag->setThumb($thumb->thumb->getThumbURL());

     $group = $proj->getGroup();
     if($group->isMember($_SESSION['user']->codeUser) == FALSE) {
       
       $_REQUEST['action'] = "A_error_report";
       $error_report = "not_a_project_member";

     } else {
       /*
	*Verificacao do realpath do diretorio
	*/
       $real = AMUpload::getRealPath("$pathBase/projects/project_".$_REQUEST['frm_codeProjeto']);
       
       $UPLOAD_DIR = new AMUpload($real);
       
     }
   }catch(CMDBNoRecord $e) {
     $_REQUEST['action'] = "A_error_report";
     $error_report = "project_not_exists";
   }

   break;

 case "user":

   $pag = new AMTUpload("top_meu_webfolio.gif");
   if(!empty($_SESSION['user'])) {
     /*
      *Verificacao do realpath do diretorio
      */
     $real = AMUpload::getRealPath("$pathBase/users/user_".$_SESSION['user']->codeUser);
            
     $UPLOAD_DIR = new AMUpload($real);
     
     $pag->setTitle($_SESSION['user']->name);
     $thumb = new AMUploadThumb;
     $thumb->codeFile = $_SESSION['user']->picture;
     $thumb->load();
     $pag->setThumb($thumb->thumb->getThumbURL());
   }else {
     $_REQUEST['action'] = "A_error_report";
     $error_report = "course_not_exists";
   }
   break;
}

/*
 *Recupera o diretorio anterior
 */
if(!empty($_SESSION['upload']['current'])) {
  $pos = strrpos($_REQUEST['frm_dir'],"/");
  if ($pos===0)
    $dir_pai = "";
  else
    $dir_pai = substr($_REQUEST['frm_dir'],0,$pos);
}

$linkBack = "<center><a href='$_SERVER[HTTP_REFERER]' class='cinza'>&laquo;$_language[back]</a><center>";

/*
 *Acoes para manipulacao de diretorios
 */
if(!isset($_REQUEST['action'])) $_REQUEST['action']='';
switch($_REQUEST['action']) {

 default:
   /*
    *Listando arquivos
    */
   
   $dir = $UPLOAD_DIR->readDir($_SESSION['upload']['current']);

   //$pag->add("<a href='$urlBase&frm_dir=$dir_pai'>voltar</a><br>");

   /*
    *Caixa com a listagem dos arquivos do diretorio atual
    */
   $pag->add(new AMBUpload($dir));
   
   /*
    *Form para envio de arquivos
    */
   $pag->add(new AMBUploadFloatingBox);

   $pag->addPageBegin(CMHTMLObj::getScript("images_url = '$_CMAPP[images_url]';"));

   break;

 case "A_create_dir":
   try {
     $UPLOAD_DIR->createDir($_SESSION['upload']['current']."/".$_REQUEST['frm_dirName']);
     header("Location:$urlBase&frm_ammsg=diretory_created_success&frm_dirName=$_REQUEST[frm_dirName]&frm_dir=$_REQUEST[frm_dir]");
   }catch(AMException $e) {
     $pag->addError(str_replace("{DIR}","<u>".$_REQUEST['frm_dirName']."<u>",$_language['error_cannot_create_diretory'])); 
     $pag->addError($_language['contact_errors']);
     $pag->add($linkVoltar);
   }
   
   break;
    
 case "A_delete":
   try {
     $UPLOAD_DIR->removeFiles($_REQUEST['frm_dir']);
     header("Location:$urlBase&frm_ammsg=files_removed_success&frm_dir=".$_REQUEST['frm_dir']."&frm_dirName=$_REQUEST[frm_dir]");
   }catch(AMException $e) {
     $pag->addError(str_replace("{DIR}","<u>".$_REQUEST['frm_dir']."</u>",$_language['error_cannot_delete_diretory']));
     $pag->addError($_language['contact_errors']);
     $pag->add($linkBack);
   }
      
   break;
 case "A_send_files":
   try {
     $UPLOAD_DIR->setUploadDir($_SESSION['upload']['current']);
     $errors = $UPLOAD_DIR->loadFileFromRequest("frm_file");
     if(!empty($errors['error'])) {
       foreach($errors as $err) {
	 $pag->addError(str_replace("{FILE}","<u>".$err[1]."</u>",$_language["error_".$err[0]]));
	 $pag->addError($_language['verify_errors']);
       }

       ($_REQUEST['frm_upload_type']=="user" ?
	AMUpload::registerLog(AMLogUploadFiles::ENUM_UPLOADTYPE_USER, time(), $_SESSION['user']->codeUser) :
	AMUpload::registerLog(AMLogUploadFiles::ENUM_UPLOADTYPE_PROJECT, time(), $_REQUEST['frm_codeProjeto'])
	);
       
       $pag->add($linkBack);
     }else header("Location:$urlBase&frm_ammsg=upload_success&frm_dir=".$_REQUEST['frm_dir']);
   }catch (AMException $e){
     $pag->addError($e->getMessage());
     $pag->addError($_language['contact_errors']);
     $pag->add($linkBack);
   }
   
   break;
 case "A_download":
   //o download sera na forma de uma arquivo zip caso
   //forem mais de um arquivo, o usuario marcou para download como zip ou for um diretorio
   
   $FILES = $UPLOAD_DIR->getFilesDownload();
   
   if (count($FILES) > 1 || $FILES[0]['mime'][0] == "pasta") {
     
     $fileName = "/tmp/download".session_id().".zip";
     //download como zip
     $command = $UPLOAD_DIR->toZip($FILES, $fileName);
     //echo $command;die();
     exec($command);
     
     header("Content-type: application/zip-file");
     if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE"))
       header("Content-Disposition: filename=download.zip" . "%20"); // For IE
     else
       header("Content-Disposition: attachment; filename=download.zip"); // For Other browsers           
     $flagZip = true;
     
     header("Content-Length: ".filesize($fileName));
     readfile($fileName);     
   } else {
     //apenas um arquivo para download. baixar o arquivo sem compactar
     header("Content-type: application/force-download");
     if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE"))
       header("Content-Disposition: filename=".$FILES[0]['name']."%20"); // For IE
     else 
       header("Content-Disposition: attachment; filename=".$FILES[0]['name']); // For Other browsers   
     header("Content-Length: ".filesize($FILES[0]['path']));     
     readfile($FILES[0]['path']);
   }

   if ($flagZip) {
     //apaga arquivo temporario (o .zip)
     exec("rm $fileName");
   }
   
   break;
 case "A_unzip_file":
   
   $UPLOAD_DIR->unZip($_REQUEST['frm_filename'], $_SESSION['upload']['current']);
   header("Location:$urlBase&frm_ammsg=upload_unzip_success&frm_dir=".$_REQUEST['frm_dir']);
   
   break;

 case "A_create_file":
   
   $pag->requires("upload.js", CMHTMLObj::MEDIA_JS);
   $pag->addPageBegin(CMHTMLObj::getScript("lang_save = '$_language[save]';"));
   $pag->addPageBegin(CMHTMLObj::getScript("lang_cancel = '$_language[cancel]';"));
   $pag->addPageBegin(CMHTMLObj::getScript("imlang_url = '$_CMAPP[imlang_url]';"));
   $pag->addPageBegin(CMHTMLObj::getScript("images_url = '$_CMAPP[images_url]';"));
   $pag->addPageBegin(CMHTMLObj::getScript("chooserUrl = '$_CMAPP[services_url]/upload/choose_image.php';"));
 
   $form = new CMWSmartForm("","form_file", $_SERVER['PHP_SELF']);

   $form->setSubmitOff();
   $form->setCancelOff();
   
   $form->addComponent("file_content", new CMWHTMLArea("frm_file_content",400,550));
   $form->addComponent("upload_type", new CMWHidden("frm_upload_type", $_REQUEST['frm_upload_type']));
   $form->addComponent("dir", new CMWHidden("frm_dir", $_REQUEST['frm_dir']));
   if(isset($_REQUEST['frm_codeProjeto'])) {
     $form->addComponent("codeProjeto", new CMWHidden("frm_codeProjeto", $_REQUEST['frm_codeProjeto']));
     $pag->addPageBegin(CMHTMLObj::getScript("var codeProjeto = '$_REQUEST[frm_codeProjeto]';"));
   } else $pag->addPageBegin(CMHTMLObj::getScript("var codeProjeto = '';"));
   //$form->addComponent("codeCourse", new CMWHidden("frm_codeCourse", $_REQUEST['frm_codeCourse']));
   $form->addComponent("filename", new CMWHidden("frm_filename",$_REQUEST['frm_filename']));
   $form->addComponent("action", new CMWHidden("action","A_save_file"));

   $form->setSubmitButtonLabel($_language['save']);
   $form->setCancelUrl($urlBase."&frm_dir=$_REQUEST[frm_dir]");

   $pag->add($form);
   
   break;

 case "A_open_file":
   
   if(!isset($_REQUEST['codeProjeto'])) $_REQUEST['codeProjeto']="";

   $pag->requires("upload.js", CMHTMLObj::MEDIA_JS);
   $pag->addPageBegin(CMHTMLObj::getScript("lang_save = '$_language[save]';"));
   $pag->addPageBegin(CMHTMLObj::getScript("lang_cancel = '$_language[cancel]';"));
   $pag->addPageBegin(CMHTMLObj::getScript("imlang_url = '$_CMAPP[imlang_url]';"));
   $pag->addPageBegin(CMHTMLObj::getScript("images_url = '$_CMAPP[images_url]';"));
   $pag->addPageBegin(CMHTMLObj::getScript("codeProjeto = '$_REQUEST[frm_codeProjeto]';"));
   //$pag->addPageBegin(CMHTMLObj::getScript("codeCourse = '$_REQUEST[codeCourse]';"));
   $pag->addPageBegin(CMHTMLObj::getScript("chooserUrl = '$_CMAPP[services_url]/upload/choose_image.php';"));

   $form = new CMWSmartForm("","form_file", $_SERVER['PHP_SELF']);
   
   $form->setSubmitOff();
   $form->setCancelOff();
   
   if(!isset($_REQUEST['frm_codeProjeto'])) $_REQUEST['frm_codeProjeto']="";
 
   $form->addComponent("file_content", new CMWHTMLArea("frm_file_content",400,550));
   $form->addComponent("upload_type", new CMWHidden("frm_upload_type", $_REQUEST['frm_upload_type']));
   $form->addComponent("dir", new CMWHidden("frm_dir", $_REQUEST['frm_dir']));
   $form->addComponent("codeProjeto", new CMWHidden("frm_codeProjeto", $_REQUEST['frm_codeProjeto']));
   //$form->addComponent("codeCourse", new CMWHidden("frm_codeCourse", $_REQUEST['frm_codeCourse']));
   $form->addComponent("filename", new CMWHidden("frm_filename",$_REQUEST['frm_filename']));
   $form->addComponent("action", new CMWHidden("action","A_save_file"));
   
   $form->setSubmitButtonLabel($_language['save']);
   $form->setCancelUrl($urlBase."&frm_dir=$_REQUEST[frm_dir]");

   $filePath = $_SESSION['upload']['current']."/$_REQUEST[frm_filename]";
   
   switch($_REQUEST['frm_upload_type']) {
   case "user":
     $url = $_CMAPP['pages_url']."/users/user_".$_SESSION['user']->codeUser;
     break;
   }
   
   
   $foldersPath = AMUpload::getRAPaths(AMUpload::ABSOLUTE_PATHS);

   $fileContent = implode("",file($filePath));
   
   foreach($foldersPath as $path) {
     if(empty($path['relative'])) $path['relative'] = "./";//patern = $url;
     $patern = $url.$path['absolute'];
     $fileContent = str_replace($path['relative'], $patern, $fileContent);
   }
   
   $form->components['file_content']->setValue($fileContent);

   $pag->add($form);
   
   break;

 case "A_save_file":
   
   switch($_REQUEST['frm_upload_type']) {
   case "user":
     $rootFolder = "users/user_".$_SESSION['user']->codeUser;
     break;
   case "project":
     $rootFolder = "projects/projeto_";
     break;
   }

   $filePath = $_SESSION['upload']['current']."/$_REQUEST[frm_filename]";
   
   $foldersPath = AMUpload::getRAPaths();
   
   $fileContent = stripslashes($_REQUEST['frm_file_content']);
   
   foreach($foldersPath as $path) {
     if($path['absolute'] == "$_REQUEST[frm_dir]/") $path['relative'] = "./";
     $patern = $_CMAPP['pages_url']."/$rootFolder".$path['absolute'];
     $fileContent = str_replace($patern, $path['relative'], $fileContent);
   }
   
   $UPLOAD_DIR->saveFile($filePath, $fileContent);

   ($_REQUEST['frm_upload_type']=="user" ?
    AMUpload::registerLog(AMLogUploadFiles::ENUM_UPLOADTYPE_USER, time(), $_SESSION['user']->codeUser) :
    AMUpload::registerLog(AMLogUploadFiles::ENUM_UPLOADTYPE_PROJECT, time(), $_REQUEST['frm_codeProjeto'])
    );
   
   header("Location:$urlBase&frm_ammsg=upload_save_file_success&frm_dir=".$_REQUEST['frm_dir']);
      
   break;
   
   
 case "A_error_report":
   
   $pag->addError($_language["error_$error_report"]);
  
   $pag->add("<center><a href='$_CMAPP[url]' class='cinza'>&laquo;$_language[voltar]</a><center>");
   
   break;
}

echo $pag;