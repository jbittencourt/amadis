<?
//$autentica = -1;
include_once("../../config.inc.php");
include_once("$rdpath/smartform/wsmartform.inc.php");
include_once("Date/Calc.php");
include_once("$pathuserlib/templ_paginas.inc.php");
include_once("$rdpath/base/rdagenda.inc.php");
include_once("$pathuserlib/amcompromisso.inc.php");

$ui = new RDui("compromissos",$_REQUEST[acao]);
$lang = $_SESSION[ambiente]->getLangUI($ui);

$pag = new AMTemplateComprimissos();

class compromisso extends RDObj { 
  function compromisso($key="") {
    $pkFields = "codCompromisso";
    $fgKFields = "";
    $fields_def = array();
    $fields_def[codCompromisso] = array("type" => "int","size" => "11","bNull" => "0");
    $fields_def[codUser] = array("type" => "int","size" => "11","bNull" => "0");
    $fields_def[nomCompromisso] = array("type" => "varchar","size" => "40","bNull" => "0");
    $fields_def[desCompromisso] = array("type" => "tinytext","size" => "","bNull" => "1");
    $fields_def[codProjeto] = array("type" => "int","size" => "11","bNull" => "1");
    $fields_def[codOficina] = array("type" => "int","size" => "11","bNull" => "1");
    $fields_def[timeDATA] = array("type" => "int","size" => "11","bNull" => "0");
    $this->RDObj($this->getTables(),$this->getFields(),$pkFields,$key,$fields_def,$fgKFields);
  }
  function getTables() {
    return "compromisso";
  }
  function getFields() {
    return  array("codCompromisso","codUser","nomCompromisso","desCompromisso","codProjeto","codOficina","timeDATA");
  }
}


//------ DESIGN --------
//include_once("$pathtemplates/amtoficinas.inc.php");
include_once("$pathtemplates/amtagenda.inc.php");
/*
$ui = new RDui ("agenda", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);
*/
$pag = new AMTAgenda();  
$pag->addScript("
function desmarcaProjeto() {

     document.compromisso.frm_codProjeto.value = \"0\";

}

function desmarcaOficina() {

     document.compromisso.frm_codOficina.value = \"0\";

}       
");
$pag->add ("<br>");
//------ FIM DESIGN --------

echo ($_SESSION[usuario]->codUser);
switch($_REQUEST[acao]) {

 case "A_criar_compromisso":


   $listaP = $_SESSION[usuario]->listaProjetos();
   $listaOF = $_SESSION[usuario]->listaOficinas();
   $tab = new waBox(1);
   $tab->setColor("verde");
   $tab->setTipoTitulo("unico", "light");
   $tab->asTable=0;
   $tab->setFullPage();
   $tab->setTitulo($lang[cria_compromisso]);

   $coordenador = new AMUser();
   $coord = $coordenador->buscaCoordenador();
   if (empty($coord->records)){
     $codOficina = 'codOficina';
   }else{
     $codOficina = '';
   }
   if(empty($_REQUEST[frm_codCompromisso])) {
     $nao = array("codCompromisso","codUser",$codUser);
     $hidden = $codOficina;
   } else {
     $nao = array($codOficina);
     $hidden = array("codCompromisso","codUser");
   }

   $form = new WSmartForm("AMCompromisso","compromisso",$_SERVER[PHP_SELF]."?acao=A_salva_compromisso",$nao,$hidden);
   $form->setDesign("WFORMEL_DESIGN_OVER");//$rdpatch/smartform/wformel.inc.php
   if(!empty($_REQUEST[frm_codCompromisso])) {
     $comp = new AMCompromisso($_REQUEST[frm_codCompromisso]);
     if($comp->novo==0) $form->loadDataFromObject($comp);
   }

   $form->setDate("timeDATA","h:i &nb\sp;  d/F/Y");
   $form->componentes[timeDATA]->setCalendarOn();
   $form->componentes[timeDATA]->setValue(time());
   $form->componentes[timeDATA]->fdesign = 'WFORMEL_DESIGN_OVER';
   //------- Radio Group de Projetos
   $projeto = new AMambiente();
   $form->add("<!- Inicio do RadioGroup -- > Associar ao Projeto");

   $form->setSelect("codProjeto",$listaP,"codProjeto","desTitulo");//  {$rdpatch/smartform/WSmartform |  function setSelect($field,$options,$index="",$list="")
   $form->add('onChange');
   $form->componentes[codProjeto]->addOption('0','nenhum');//$rdpatch/smartform/wselect

   //   $coordenador = new AMUser();
   //   $coord = $coordenador->buscaCoordenador();
   if (empty($codOficina)){
     $form->setSelect("codOficina",$listaOF,"codOficina","nomOficina");
     $form->componentes[codOficina]->addOption('0','nenhuma');
   }

   $form->componentes[codProjeto]->prop[onChange] = "desmarcaOficina()";
   $form->componentes[codOficina]->prop[onChange] = "desmarcaProjeto()";
   $tab->add($form);
   $pag->add($tab);

   $pag->imprime();
   exit;
   break;


 case "A_salva_compromisso":
   if (empty($_REQUEST[frm_codCompromisso]))
     $comp = new AMCompromisso();//AMCompromisso();
   else
     $comp = new  AMCompromisso($_REQUEST[frm_codCompromisso]);/* RDCompromisso($_REQUEST[frm_codCompromisso]);*/

   $comp->codUser = $_SESSION[usuario]->codUser;
   $comp->loadDataFromRequest();
   $comp->salva();
   break;

 case "A_deleta_compromisso":
   $comp = new AMCompromisso/* RDCompromisso*/($_REQUEST[codCompromisso]);
   $comp->deleta();
   break;

   
}


if (empty($_REQUEST[dia])) {
  $dia = date("d",time());
}   //retorna o numero do mes atual
else {
  $dia = $_REQUEST[dia];
}

if (empty($_REQUEST[mes])) {
  $mes = date("m",time());
}   //retorna o numero do mes atual
else {
  $mes = $_REQUEST[mes];
}

if (empty($_REQUEST[ano])) {
  $ano = date("Y",time());
}  //ano atual
else {
  $ano = $_REQUEST[ano];
}



$mes_cal = Date_Calc::getCalendarMonth($mes,$ano);

//como muitas vezes utiliza-se esse parâmetro pelo link montei uma string pré pronta
$link_data = "dia=$dia&mes=$mes&ano=$ano";

$pag->add(menuAuxiliar(array(array("link" => "$_SERVER[PHP_SELF]?acao=A_criar_compromisso&$link_data","texto"=>"Criar novo compromisso"))));



$pag->add('teste');

$pag->add("<TABLE width=\"100%\" border=\"0\" style=\"table-layout: auto\">");
$pag->add("<TR valign=top><TD width=\"30%\">");      
//calendario  
 
$tab = new waBox(1);
$tab->setColor("verde",'0');
$tab->setTipoTitulo("unico", "light");

$tit.="<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
$tit.="<tr>";
$tit.="<td align=\"center\" width=\"14%\" height=\"25\"><font size=\"2\"><b>S</b></font></td>";
$tit.="<td align=\"center\" width=\"14%\" height=\"25\"><font size=\"2\"><b>T</b></font></td>";
$tit.="<td align=\"center\" width=\"14%\" height=\"25\"><font size=\"2\"><b>Q</b></font></td>";
$tit.="<td align=\"center\" width=\"14%\" height=\"25\"><font size=\"2\"><b>Q</b></font></td>";
$tit.="<td align=\"center\" width=\"14%\" height=\"25\"><font size=\"2\"><b>S</b></font></td>";
$tit.="<td align=\"center\" width=\"14%\" height=\"25\"><font size=\"2\"><b>S</b></font></td>";
$tit.="<td align=\"center\" width=\"14%\" height=\"25\"><font size=\"2\"><b><span style=\"font-weight: bold; color: rgb(255, 0, 0);\">D</span></b></font></td>";
$tit.="</table>";

$tab->setTitulo($tit);
$tab->asTable=0;

$tab->add("<table border=\"0\"  cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">");
//style="text-align: left; width: 100%; height: 500px;"
$dias_compromisso = $_SESSION[usuario]->listaDiasDeCompromisso($mes,$ano);
$k = 0;
foreach ($mes_cal as $semana) { 
  $tab->add("<TR>");
  foreach ($semana as $dia_semana) {
	if ($k == 6) {
	  $domig[0] = '<span style="font-weight: bold; color: rgb(255, 0, 0);">';
	  $domig[1] = '</span>';
	} else {
	   $domig[0] = '';
	   $domig[1] = '';
	}

	if ($k == 7){
	  $k = 0;
	}
	$k++;

    $month = substr($dia_semana,4,2);
    $day = substr($dia_semana,6);
    if ($month==$mes) {
      if ($day==$dia)
	$tab->add("<td align=\"center\" width=\"14%\" height=\"25\""." bgcolor=\"#FFA042\"><font size=\"2\" color=\"#ffffff\"  class=\"regular_calendario\"><b>$day</b></font></td>");// bgcolor=\"#006699"
      else {
	$link = $_SERVER[PHP_SELF]."?dia=".$day."&mes=".$mes."&ano=".$ano;
	$classe = "regular_calendario";
	if(!empty($dias_compromisso[$day])) $classe = "regular_calendario_red";
	$tab->add("<td align=\"center\" width=\"14%\" height=\"25\"><font size=\"2\"><b><A href=\"$link\"  class=\"$classe\">".$domig[0].$day.$domig[1]."</A></b></font></td>");
      }      
    }
    else
      $tab->add("<td align=\"center\" width=\"14%\" height=\"25\"><font size=\"2\"><b>&nbsp;</b></font></td>");
  }

}
$tab->add("</TABLE>");

$tab->add("<br><TABLE><TR>");

$tab->add("<FORM name=\"form1\" action=\"$_SERVER[PHP_SELF]\" method=\"POST\">");
$tab->add("<TD><SELECT name=\"mes\" onChange=\"document.form1.submit()\">");

$tab->add("  <OPTION value=\"1\">Janeiro");
$tab->add("  <OPTION value=\"2\">Fevereiro");
$tab->add("  <OPTION value=\"3\">Mar&ccedil;o");
$tab->add("  <OPTION value=\"4\">Abril");
$tab->add("  <OPTION value=\"5\">Maio");
$tab->add("  <OPTION value=\"6\">Junho");
$tab->add("  <OPTION value=\"7\">Julho");
$tab->add("  <OPTION value=\"8\">Agosto");
$tab->add("  <OPTION value=\"9\">Setembro");
$tab->add("  <OPTION value=\"10\">Outubro");
$tab->add("  <OPTION value=\"11\">Novembro");
$tab->add("  <OPTION value=\"12\">Dezembro");
$tab->add("</SELECT></TD>");

$ano_atual = date("Y",time());
$tab->add("<TD><SELECT name=\"ano\" onChange=\"document.form1.submit()\">");
$tab->add("  <OPTION value=\"".($ano_atual-1)."\">".($ano_atual-1));
$tab->add("  <OPTION value=\"".$ano_atual."\">".$ano_atual);
$tab->add("  <OPTION value=\"".($ano_atual+1)."\">".($ano_atual+1));

$tab->add("</SELECT>");

$script = " <SCRIPT Language=\"JavaScript1.2\">";
$script.= " document.form1.mes.selectedIndex = ".($mes-1).";\n";
if ($ano_atual==$ano)
     $script.= " document.form1.ano.selectedIndex = 1;\n";
     elseif ($ano==$ano_atual-1)
     $script.= " document.form1.ano.selectedIndex = 0;\n";     
     else
     $script.= " document.form1.ano.selectedIndex = 2;\n";
$script.= " </SCRIPT>";

$tab->add($script);

$tab->add("</TD></TR></TABLE>");

$pag->add($tab);

$pag->add("</TD><TD valign=\"top\" align=\"70%\" >");

//listar compromissos
$comprom = new AMCompromisso;


$compromissos = $_SESSION[usuario]->listaCompromissos($dia,$mes,$ano);

$tab = new waBox(3);
$tab->setColor("verde",'0');
$tab->setTipoTitulo("unico", "light");


$tempoI = mktime(0,0,0,$mes,$dia,$ano);
$tempoF = mktime(23,59,59,$mes,$dia,$ano);
$tempo = time();
$amarelo = 'rgb(255, 255, 204)';//amarelo   |   style=\" background-color: $cor\"' bgcolor="#FFA042"';rgb(255, 255, 204)
$azul = 'rgb(204, 255, 255)';//azul |vermelho, verde, azul
//echo $urlimlang;

$tabelaAzul = "Meus compromissos <img src=\"$urlimlang\azul.gif\">";
$tabelaAmarela =  "Compromissos de minhas Oficinas ou Projetos <img src=\"$urlimlang\amarelo.gif";
if(($tempoI<=$tempo) and ($tempo<=$tempoF)) $hoje = "(hoje)";
$tab->add("<img src=\"$urlimlang/azul.gif\">Meus compromissos<br> ");
$tab->add("<img src=\"$urlimlang/amarelo.gif\">Compromissos de minhas Oficinas ou Projetos <hr noshade>");
//$tab->add("<td style=\"vertical-align: top; text-align: right;\">$tabelaAzul<br>$tabelaAmarela</td>");//</td></tr>");
$tab->setTitulo("$dia/$mes/$ano $hoje");

$tab->asTable=0;
$tab->borda = 0;

$tab->add("<table border=0 cellpadding=\"0\" cellspacing=\"0\" width=100%>");

if (!empty($compromissos->records)) {

  $pag->addJSFile($config_ini[Internet][urljs]."/confirma.js");

  //--------------------------------
  $t = 0;
  $projet = $comprom->listaIdProjeto();
  foreach ($projet->records as $pro){
    $codP[] = $pro->codProjeto;
  }
  session_start();
  session_register("CODP");
  $CODP = $codP;

  $x = 0;
  $tab->add("");
  foreach ($compromissos->records as $comp) {
    $x++;
    $nomeProjeto[$x] = $comprom->buscaNomeProjeto($comp->codProjeto);//$comp->codProjeto
    $nomeOficina[$x] = $comprom->buscaNomeOficina($comp->codOficina);
    //   if ($nomeProjeto[$x] != 'nenhum' &&  $nomeOficina[$x] != 'nenhum'){
    $titulo = '';
    $titulo = '<br> &nbsp; &nbsp;';
    if ($nomeProjeto[$x] != 'nenhum'){
      $titulo.= 'Vinculado ao projeto: '.$nomeProjeto[$x];
    } else if ($nomeOficina[$x] != 'nenhum'){
      $titulo.= 'Vinculado a oficina: '.$nomeOficina[$x];
    } else {
      $titulo = '';
    }
    //   }
    $link_altera = $_SERVER[PHP_SELF]."?acao=A_criar_compromisso&frm_codCompromisso=".$comp->codCompromisso;
    $link_exclui = "javascript:confirma_redir('Voce tem certeza de que deseja deletar este compromisso?','".$_SERVER[PHP_SELF]."?acao=A_deleta_compromisso&codCompromisso=".$comp->codCompromisso."&$link_data ')";
  
    $acoes = "<A href=\"".$link_altera."\">[Editar]</A>&nbsp;";
    $acoes.= "<A href=\"".$link_exclui."\">[Excluir]<A>";


    if ($_SESSION[usuario]->codUser != $comp->codUser){
      $cor = $amarelo;
      if ($comp->codOficina != 0){
	$acoes = '';
      }
    } else {
      $cor = $azul;
    }
    $hora = date("h:i",$comp->timeDATA);

    $class = "class=\"$tab->cor\"";
    //      //----comença a escrever compromissos
    //      $tab->add("<TR style=\" background-color: $cor\" $class><td  width=\"15%\">$hora</td><td><span style=\"font-weight: bold;\">".$comp->nomCompromisso."</span><br> &nbsp; &nbsp; Vinculado a:<br> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Projeto: ".$nomeProjeto[$x]."<br>"."&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Oficina: ".$nomeOficina[$x]." </TD><td align=right><font size=-2>$acoes</font></td></TR>");
    //      $tab->add("<tr  style=\" background-color: $cor\"><td>&nbsp;</td><td colspan=2>$comp->desCompromisso</td></tr>");
    //      //-----Fim de escrever compromissos  
    //----comença a escrever compromissos
    $tab->add("<TR style=\" background-color: $cor\" $class><td  width=\"50\">$hora</td><td><span style=\"font-weight: bold; font-family: helvetica,arial,sans-serif;\">".$comp->nomCompromisso."</span><small><span style=\"font-family: helvetica,arial,sans-serif;\">$titulo</span></small></TD><td align=right><font size=-2>$acoes</font></td></TR>");
    $tab->add("<tr  style=\" background-color: $cor\"><td>&nbsp;</td><td cellpadding=\"5\" cellspacing=\"5\" colspan=2><small><span style=\"font-family: helvetica,arial,sans-serif;\"><blockquote>$comp->desCompromisso</blockquote></span></small><hr noshade></td></tr>");
    //-----Fim de escrever compromissos   
  }

}else {
  $tab->add("<tr><td>$lang[nenhum_compromisso]</tr></td>");
};
$tab->add("</table>");
$tab->add("</table>");
$pag->add($tab);
$pag->imprime();
?>