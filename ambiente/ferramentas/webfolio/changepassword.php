<?
include("../../config.inc.php");
include("cmwebservice/cmwemail.inc.php");


$_language = $_CMAPP['i18n']->getTranslationArray("changepassword");


$pag = new AMTCadastro();

/*
 *Load language module
 */
    
$_CMAPP['smartform'] = array();
$_CMAPP['smartform']['language'] = $_language;
    

//form box to interface
if(!isset($_REQUEST['action'])) $_REQUEST['action'] = '';
switch($_REQUEST['action']) {
 case "change_password":

   if(md5($_REQUEST['frm_password'])!=$_SESSION['user']->password) {
     header("Location:$_SERVER[PHP_SELF]?frm_amerror=old_password_problem");
   }

   if(empty($_REQUEST['frm_new_password'])) {
     header("Location:$_SERVER[PHP_SELF]?frm_amerror=empty_password");
   }

   $temp =  $_SESSION['user']->password;
   try {
     $_SESSION['user']->password = $_REQUEST['frm_new_password'];
     $_SESSION['user']->save();
     header("Location: $_CMAPP[services_url]/webfolio/webfolio.php?frm_ammsg=password_change_sucesfull");
   }
   catch(CMDBException $e) {
     echo $e; die();
     $_SESSION['user']->password = $temp;
     header("Location:$_SERVER[PHP_SELF]?frm_amerror=saving_password");
   }
   
   break;
}

$cadBox = new AMTCadBox($_language['change_password'], $image=AMTCadBox::CADBOX_CREATE, $theme=AMTCadBox::WEBFOLIO_THEME);



//$fields_rec = array("password");
      
//formulary
$form = new CMWSmartForm("AMUser","change_pass",$_SERVER['PHP_SELF']);


$form->setDesign(CMWFormEL::WFORMEL_DESIGN_OVER);
$form->setLabelClass("fontgray");


$pwd = new CMWText("frm_password","",20,10);
$pwd->setPassword();
$form->addComponent("password",$pwd);

$pwd = new CMWText("frm_new_password","",20,10);
$pwd->setPassword();
$form->addComponent("new_password",$pwd);
      
$rpwd = new CMWText("frm_re_password","",20,10);
$rpwd->setPassword();
$form->addComponent("re_password", $rpwd);
     
$form->addComponent("action",new CMWHidden("action","change_password"));

$form->forceCheckField(array("password","new_password","re_password"));

$form->submit_label = $_language['next'];
$form->addOnSubmitAction("passwd_validate(this.elements['frm_new_password'], this.elements['frm_re_password'])");  
$form->setCancelUrl($_CMAPP['services_url']."/webfolio/webfolio.php");

$cadBox->add($form);

      
$pag->add($cadBox);
echo $pag;

?>