<?

$sem_login = "1";

include("../../config.inc.php");
include_once("$rdpath/smartform/wsmartform.inc.php");
include_once("$pathtemplates/ambox.inc.php");
include_once("$pathtemplates/amcolorbox.inc.php");
include_once("$pathtemplates/ammain.inc.php");
include_once("$pathtemplates/ambox.inc.php");

$ui = new RDui("admin", "");
$lang = $_SESSION[environment]->getLangUI($ui);

$pag = new AMMain();

$tab = new AmColorBox("azul2");
$box = new AMBox();


switch ($_REQUEST[pagina]) {
  
 default :
   switch ($_REQUEST[erro]) {

   case "campos_vazios" :
     $tab->add ("<br><center><font color=red>$lang[campos_vazios]</font></center>");
     break;

   case "not_found" :
     $tab->add ("<br><center><font color=red>$lang[not_found]</font></center>");
     break;
   }
   
   $empty_list = array("codUser","desSenha","desSenhaPlain","strMaildir","codPlataforma","flaAprovado","flaAtivo","flaHomedir","","tempo","strEMail","desEndereco","codCidade","desCEP","desTelefone","desFax","desCargo","desHistorico","desUrl","codEscola","datNascimento");

   $tab->setTitle("<br>$lang[recuperar_senha]<br><br><font size=2>$lang[instrucoes]</font>");
   $form = new WSmartForm("AMUser","pagina1","$urlferramentas/admin/recuperarsenha.php?pagina=1",$empty_list);
   $form->setDesign(WFORMEL_DESIGN_OVER);
   $form->setCancelUrl("$url");
   $form->forceNotCheckField("nomUser");
   $form->forceNotCheckField("nomPessoa");
   $form->forceNotCheckField("strEMailAlt");
   $tab->add($form);
   $pag->add($tab);
   $pag->imprime();
   die();

   break;

 case 1 :

   global $config_ini;

   $menu["$lang[voltar]"] = "$urlferramentas/admin/recuperarsenha.php";   
   $pag->setSubMenu($menu);
   
   $tab->setTitle("<br><font size=3>$lang[escolha].</font><br>");

   $a = "<a class=fontgray href=$urlferramentas/admin/recuperarsenha.php?pagina=2";
   $ab = "</a>";
   $class = "class=\"fontgray\"";
   $font = "<font size=2><b>";
   $ffont = "</b></font>";
   $tr = "<tr>";
   $trb = "</tr>";
   $td = "<td $class>";
   $tdb = "</td>";
   
   $tableHeader = "<br><table cellspacing=0 cellpading=0 border=0 width=\"100%\"> $tr\n";
   $tableHeader .= "$td $font $lang[apelido] $ffont $tdb";
   $tableHeader .= "$td $font $lang[nome_pessoa] $ffont $tdb $td $font $lang[email] $ffont $tdb $trb\n";
   
   if(!empty($_REQUEST[frm_nomUser])) {

     $t = AMUser::getTables();
     $chave[] = opval("nomUser","%$_REQUEST[frm_nomUser]%","","LIKE");
     $campos = array("nomUser","nomPessoa","codUser","strEMailAlt");
     $lista = $_SESSION[environment]->listaUsuariosPlataforma($_SESSION[Plataforma]->codPlataforma,$chave,$campos);;
     
     if(!empty($lista->records)) {

       foreach($lista->records as $user) {
	 $text .= "$tr $td $user->nomUser $tdb\n";
	 $text .= "$td $user->nomPessoa $tdb\n";
	 if(!empty($user->strEMailAlt))
	   $text .= "$td $a&frm_codUser=$user->codUser&frm_nomUser=$_REQUEST[frm_nomUser]> $user->strEMailAlt $ab $tdb\n";
	 else $text .= "$td $lang[nao_email]nao tem email $tdb\n";
	 
	 $text .= "$trb";
       }
       echo $lang[apelido];
       unset($_REQUEST[frm_nomPessoa]);
       unset($_REQUEST[frm_strEMailAlt]);
       
     }else{
       
       header ("Location: recuperarsenha.php?erro=not_found");

     }
     
   } else if(!empty($_REQUEST[frm_nomPessoa])) {
     
     $t = AMUser::getTables();
     $chave[] = opval("nomPessoa","%$_REQUEST[frm_nomPessoa]%","","LIKE");
     $campos = array("nomUser","nomPessoa","codUser","strEMailAlt");
     $lista = $_SESSION[environment]->listaUsuariosPlataforma($_SESSION[Plataforma]->codPlataforma,$chave,$campos);;
     
     if(!empty($lista->records)){
       
       foreach($lista->records as $user){
	 $text .= "$td $user->nomUser $tdb\n";
	 $text .= "$td $user->nomPessoa $tdb\n";
	 if(!empty($user->strEMailAlt))
	   $text .= "$td $a&frm_codUser=$user->codUser&frm_nomPessoa=$_REQUEST[frm_nomPessoa]> $user->strEMailAlt $ab $tdb\n";
	 else $text .= "$td $lang[nao_email]nao tem email $tdb\n";
	 
	 $text .= "$trb";
       }
       
     }else{
       
       header ("Location: recuperarsenha.php?erro=not_found");
       
     }
     
     unset($_REQUEST[frm_nomUser]);
     unset($_REQUEST[frm_strEMailAlt]);
     
   } else if(!empty($_REQUEST[frm_strEMailAlt])) {
     
     $t = AMUser::getTables();
     $chave[] = opval("strEMailAlt","%$_REQUEST[frm_strEMailAlt]%","","LIKE");
     $campos = array("nomUser","nomPessoa","codUser","strEMailAlt");
     $lista = $_SESSION[environment]->listaUsuariosPlataforma($_SESSION[Plataforma]->codPlataforma,$chave,$campos);;
     
     if(!empty($lista->records)){
       
       foreach($lista->records as $user){
	 $text .= "$td $user->nomUser $tdb\n";
	 $text .= "$td $user->nomPessoa $tdb\n";
	 if(!empty($user->strEMailAlt))
	   $text .= "$td $a&frm_codUser=$user->codUser&frm_strEMail=$_REQUEST[frm_strEMailAlt]> $user->strEMailAlt $ab $tdb\n";
	 else $text .= "$td $lang[nao_email]nao tem email $tdb\n";
	 
	 $text .= "$trb";
       }
       
       
     }else{
       
       header ("Location: recuperarsenha.php?erro=not_found");
       
     }
     
     unset($_REQUEST[frm_nomPessoa]);
     unset($_REQUEST[frm_nomUser]);
     
   } else {
     
     header ("Location: recuperarsenha.php?erro=campos_vazios");
     
   }

   break;
   
   
 case 2 :
   
   if(!empty($_REQUEST[frm_nomUser])){
  
     $menu["$lang[voltar]"] = "$urlferramentas/admin/recuperarsenha.php?frm_nomUser=$_REQUEST[frm_nomUser]&pagina=1";
     $pag->setSubMenu($menu);

   }else if(!empty($_REQUEST[frm_nomPessoa])){
   
     $menu["$lang[voltar]"] = "$urlferramentas/admin/recuperarsenha.php?frm_nomUser=$_REQUEST[frm_nomPessoa]&pagina=1";
     $pag->setSubMenu($menu);

   }else if(!empty($_REQUEST[frm_strEMailAlt])){
   
     $menu["$lang[voltar]"] = "$urlferramentas/admin/recuperarsenha.php?frm_nomUser=$_REQUEST[frm_strEMailAlt]&pagina=1";
     $pag->setSubMenu($menu);

   }

   $a = "<a class=fontgray href=$urlferramentas/admin/recuperarsenha.php?pagina=2&acao=A_envia";
   $ab = "</a>";
   $class = "class=\"fontgray\"";
   $font = "<font size=3><b>";
   $ffont = "</b></font>";
   $tr = "<tr>";
   $trb = "</tr>";
   $td = "<td $class>";
   $tdb = "</td>";
   
   $tableHeader = "<br><table cellspacing=0 cellpading=0 border=0 width=\"100%\"> $tr\n";
   
   $user =  new AMUser($_REQUEST[frm_codUser]);
   if($_REQUEST[acao]=="A_envia"){
     

     $novasenha = rand(100000, 999999);
     $user->desSenha = $novasenha;
     $user->salva();


     
     $sendmail_from = $lang[mail_from];

     //tentativa de funcionar as entidades pro email:
     $a = htmlentities($lang[mail_corpo1], ENT_NOQUOTES, "win-1251");
     $decod_mail_corpo1 = html_entity_decode($a);

     $b = htmlentities($lang[mail_corpo2], ENT_NOQUOTES, "win-1251");
     $decod_mail_corpo2 = html_entity_decode($b);\

     $c = htmlentities($lang[mail_corpo3], ENT_NOQUOTES, "win-1251");
     $decod_mail_corpo3 = html_entity_decode($c);

     // - - - - - - - - - - - - - - - - - - - - - - - - - - -

     $enviar = mail($user->strEMailAlt, $lang[mail_titulo], $decod_mail_corpo1."\n\n".$decod_mail_corpo2."\n\n".$novasenha."\n\n".$decod_mail_corpo3, "From: AMADIS");
     $tableHeader .= "$td <center>$font $lang[nova_senha_enviada] $ffont </center> <br><br> $tdb $trb";
     $tableHeader .= "$tr $td <center> $font  $user->strEMailAlt $ffont </center><br><br> $tdb $trb";
     $tableHeader .= "$tr $td <center><a class=fontgray href=$url>$lang[voltar_principal]</a></center> $tdb $trb";


   }else{

     $tableHeader .= "$td <center> $font $lang[enviar_para] $ffont </center><br> $tdb $trb";
     $tableHeader .= "$tr $td <center> $a&frm_codUser=$user->codUser> $user->strEMailAlt $ab </center> $tdb $trb";
     
   }

   break;   

}

$tableHeader .= $text;
$tableHeader .= "</table>";
$box->add($tableHeader);
$tab->add($box);   
$pag->add($tab);
$pag->imprime();

?>