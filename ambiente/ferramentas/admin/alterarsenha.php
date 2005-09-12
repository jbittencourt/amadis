<?

include("../../config.inc.php");
include_once("$rdpath/smartform/wsmartform.inc.php");
include_once("$pathtemplates/amcolorbox.inc.php");
include_once("$pathtemplates/amtcadastro.inc.php");

$ui = new RDui("cadastro", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);

$pag = new AMTCadastro();

$pag->add("<br><table width=100% border=0 cellpadding=0 cellspacing=0>");

//coluna da esquerda
$pag->add("<tr><td class=\"fontgray\"");
$pag->add(" valign=\"top\">");

$tab = new AMColorBox("azul2");
$tab->setTitle("<br>$lang[alterar_senha]");

if ($_SESSION[usuario]) {
  
  $user = $_SESSION[usuario];
  
  switch ($_REQUEST[pagina]) {
  default :

    if ($_REQUEST[erro] == "nao") {
      $pag->add ("<center><font color=red>senha errada".$lang[senha_errada]."</font></center><br><br>");
    }

    $ocultos = array("nomUser","desSenha","codUser","nomPessoa","tempo","strEMail","desEndereco","codCidade","codEstado","desCEP","desTelefone","desFax","desCargo","desHistorico","desUrl","codEscola","datNascimento","flaAtivo","flaAprovado","desSenhaPlain","codPlataforma","strMaildir","flaHomedir","strEMailAlt");


    $form = new WSmartForm("AMUser","alterarsenha1","$urlferramentas/admin/alterarsenha.php?pagina=1",$ocultos);

    $form->setDesign(WFORMEL_DESIGN_OVER);
    
       
    $passOld = new WText("frm_passOld","","15","15");
    $passOld->setPassword();
    $passOld->addLabel("senha atual$lang[senha_atual]");
    $form->addComponent("frm_passOld", $passOld);
    $form->setCancelUrl("$url/inicial.php");
    $tab->add($form); 
    $pag->add($tab);

  break;

 case 1 :

   if ($_REQUEST[erro] == "nao") {
      $pag->add ("<center><font color=red>nao confere".$lang[nao_conferem]."</center></font><br><br>");
    }

   if (md5($_REQUEST[frm_passOld]) == $user->desSenha or $_REQUEST[jah] == "jah") {

     $ocultos = array("nomUser","desSenha","codUser","nomPessoa","tempo","strEMail","desEndereco","codCidade","codEstado","desCEP","desTelefone","desFax","desCargo","desHistorico","desUrl","codEscola","datNascimento","flaAtivo","flaAprovado","desSenhaPlain","codPlataforma","strMaildir","flaHomedir","strEMailAlt");
     
     $form  = new WSmartForm("AMUser","alterarsenha2","$urlferramentas/admin/alterarsenha.php?pagina=2&acao=A_trocar_senha",$ocultos);    

     $form->setDesign(WFORMEL_DESIGN_OVER);

     $passNew = new WText("frm_passNew","",15,15);
     $passNew->addLabel("$lang[senha_nova]");
     $passNew->setPassword();
     $form->addComponent("frm_passNew",$passNew);
     $form->setCancelUrl("$urlferramentas/admin/alterarsenha.php");

     $passNewAgain = new WText("frm_passNewAgain","",15,15);
     $passNewAgain->addLabel("$lang[resenha_nova]");
     $passNewAgain->setPassword();
     $form->addComponent("frm_passNewAgain",$passNewAgain);
     $form->setCancelUrl("$urlferramentas/admin/alterarsenha.php");

     $tab->add($form);
     $pag->add($tab);
   } else {
     header ("location: $urlferramentas/admin/alterarsenha.php?erro=nao");
   }


  break;

 case 2 :

   if (!empty($_REQUEST[acao]) == "A_trocar_senha") {

     if ($_REQUEST[frm_passNew] == $_REQUEST[frm_passNewAgain]) {
       $user->desSenha = $_REQUEST[frm_passNew];
       $user->salva();
       $pag->add ("<img src=\"$urlimagens/dot.gif\" height=\"10\" width=\"10\">");
       $pag->add ("<center><font size=3><br>$lang[senha_alterada]<br><br></font>");
       $pag->add("<a class=fontgray href=\"$url/inicial.php\">&laquo;&nbsp;$lang[voltar]</a></center>");
       $pag->add ("</td><td width=\"10%\" valign=\"top\">&nbsp;</td></tr></table>");
       $pag->imprime();
       die();
       
     } else {
       header ("location: $urlferramentas/admin/alterarsenha.php?pagina=1&erro=nao&jah=jah");
     }
   }

   break;

  }//fim do switch

}//fim do if

$pag->add("<img src=\"$urlimagens/dot.gif\"></td><td>");

$pag->add("</td></tr></table>");
   
$pag->imprime();

?>