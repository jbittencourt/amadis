<?
$_CMAPP[notrestricted] = 1;
include("../../config.inc.php");
include($_CMAPP[path]."/templates/amtcadastro.inc.php");
include($_CMAPP[path]."/templates/amcadbox.inc.php");
include($_CMAPP[path]."/lib/amarquivo.inc.php");
include($_CMAPP[path]."/lib/amimage.inc.php");
include($_CMAPP[path]."/lib/amfoto.inc.php");


$_language = $_CMAPP[i18n]->getTranslationArray("recoverPassword");


$pag = new AMTCadastro();

/*
 *Load language module
 */
    
$_CMAPP[smartform] = array();
$_CMAPP[smartform][language] = $_language;
    

// $el["default"] = $_language[pag_0];
// $el[pag_1] = $_language[pag_1];
// $el[pag_2] = $_language[pag_2];
// $el[pag_3] = $_language[pag_3];
// $ind =  new AMPathIndicator($el);
// $ind->setState($_REQUEST[action]);
// $pag->setPathIndicator($ind);


//form box to interface
$cadBox = new AMCadBox;

switch($_REQUEST[action]) {

 default:


   $fields_rec = array("username","email");
      
   //formulary
   $form = new CMWSmartForm(AMUser,"cad_user",$_SERVER[PHP_SELF],$fields_rec);

   if($_SESSION[cad_user] instanceof AMUser) {
     $form->loadDataFromObject($_SESSION[cad_user]);
   }

   $form->addOnSubmitAction("email_validate(this['frm_email'])");
   $form->setDesign(CMWFormEL::WFORMEL_DESIGN_OVER);
   $form->setLabelClass("fontgray");
   
   $form->components[username]->setValue($_REQUEST[frm_username]);
   $form->components[email]->setValue($_REQUEST[frm_email]);
     
   $form->addComponent("action",new CMWHidden("action","pag_1"));

   if($_REQUEST[override_email_check]=="1") {
     $form->addComponent("override_email_check",new CMWHidden("override_email_check","1"));
   }

   //ajust the form format
   $format = new CMHtmlFormat;
   $format->setTabela("table cellspacing=1 cellpadding=2 width=\"70%\"","/table");
   $form->setHtmlFormat($format);
   $form->components[submit_group]->setOrder(array("cancel","submit"));
   $form->components[submit_group]->setAlign("right");
   $form->submit_label = $_language[next];


   $cadBox->setTitle("box_cadastro_usuario.gif");
   $cadBox->add("<p>$_language[explanation]");
   $cadBox->add($form);
   
   
   break;
      
 case "pag_1":
   $user = new AMUser;
   $user->username = $_REQUEST[frm_username];
   $user->email = $_REQUEST[frm_email];
   try {
     $user->load();
   }catch (CMDBNoRecord $e) {
          header("Location:$_SERVER[PHP_SELF]?frm_amerror=user_not_found&frm_username=$_REQUEST[frm_username]&frm_email=$_REQUEST[frm_email]");
   }

   if((!checkdnsrr(array_pop(explode("@",$_REQUEST[frm_email])),"MX")) and (!$_REQUEST[override_email_check])) {
     header("Location:$_SERVER[PHP_SELF]?frm_amerror=email_possible_problem&frm_username=$_REQUEST[frm_username]&frm_email=$_REQUEST[frm_email]&override_email_check=1");
   }


   $user->randomPassword();
   //Prepare and send the email to the user
   //tests
   include($_CMAPP[path]."/templates/ammailmessage.inc.php");

   $mail = new AMMailMessage;
   $mail->addTo($user->email,$user->name);
   $mail->setHTMLMessage();
   $mail->setSubject($_language[recover_password_email_sub]);

   //finds the user firstname
   $temp = explode(" ",trim($user->name));
   $firstname = $temp[0];

   $keys = array("{FIRSTNAME}","{PASSWORD}");
   $values = array($firstname,$user->password);
   $mensagem = str_replace($keys,$values,$_language[recover_password_email]);
   $mail->setMessage($mensagem);


   try {
     $mail->send();
   }
   catch(CMWEmailNotSend $e) {
     header("Location:$_SERVER[PHP_SELF]?frm_amerror=sending_email&action=fatal_error");
   }


   try {
     $user->save();
   }
   catch(CMDBException $e) {
     header("Location:$_SERVER[PHP_SELF]?frm_amerror=saving_user&action=fatal_error");
   }

   $cadBox->add("<p align=center>".$_language[recover_password_success]);

   break;

 case "fatal_error":
   //No caso de um erro fatal.
   //A mensagem de erro e exibida pelo proprio template AMMain.
   $cadBox->add("<span><p align=center><a href=\"$_SERVER[PHP_SELF]\" class=\"cinza\">$_language[try_again]</a></div>");
   break;
}
   
$pag->add($cadBox);
echo $pag;

?>