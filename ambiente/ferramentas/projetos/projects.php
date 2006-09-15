<?

$_CMAPP['notrestricted'] = 1;

include("../../config.inc.php");


$_language = $_CMAPP['i18n']->getTranslationArray("projects");

$pag = new AMTProjeto;
if(!isset($_REQUEST['frm_action'])) $_REQUEST['frm_action']="";
if(isset($_REQUEST['clear_cadProj'])) unset($_SESSION['cad_proj']);

switch($_REQUEST['frm_action']) {
 default:

   $pag->add("<br>");


   /*
    *INICIO DA PAGINA
    */
   $box = new AMTwoColsLayout;

   $intro = "<span class=\"texto\"><img src=\"$_CMAPP[imlang_url]/img_projetando_amadis.gif\"><br>";
   $intro.= "        <img src=\"$_CMAPP[images_url]/dot.gif\" border=\"0\" height=\"10\" width=\"1\"><br>";
   $intro.= "        $_language[project_intro]</span><br>";

   $box->add($intro, AMTwoColsLayout::LEFT);
   $box->add('<br>', AMTwoColsLayout::LEFT);


   if($_SESSION['environment']->logged == 1){
     $link = "<a href=\"$_CMAPP[services_url]/projetos/create.php\">";
     $link.= "<img border=0 src=\"$_CMAPP[imlang_url]/img_criar_novo_projeto.gif\"><br>";
     $link.= "</a>";
     $box->add($link, AMTwoColsLayout::LEFT);
   }


   /*
    *Projetos mais visitados
    */

   $box->add('<br>', AMTwoColsLayout::LEFT);
   $box->add(new AMBProjectsTop, AMTwoColsLayout::LEFT);

   /*
    *FINAL DA COLUNA ESQUERDA
    */

   /*
    *COLUNA DIREITA
    */
   $box->add(new AMBProjectsSearch, AMTwoColsLayout::RIGHT);

//   if($_SESSION['environment']->logged == 1) {
//     $box->add(new AMBProjectMine, AMTwoColsLayout::RIGHT);
//   }

   $box->add(new AMBProjectsArea, AMTwoColsLayout::RIGHT);
   $box->add('<br><br>', AMTwoColsLayout::RIGHT);
   $box->add(new AMBProjectsCommunity, AMTwoColsLayout::RIGHT);

   $pag->add($box);
   $pag->add("<br><br>");
   break;
 case "search_result":
   $pag->add(new AMBProjectsSearch);
   break;
}

echo $pag;

?>