<?
include "../../config.inc.php"; 

include("$_CMAPP[path]/templates/amtfotolibrary.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("library");

$page = new AMTLibrary("$_REQUEST[frm_type]");

$page->addCommunicatorHandler("AMShared");

$page->addPageBegin(CMHTMLObj::getScript("var AMShare = new amshared(AMSharedCallBack)"));

$page->addPageBegin(CMHTMLOBJ::getScript("lang_wish_delete='$_language[wish_delete]'"));

switch($_REQUEST["frm_type"]) {
 case "project" :
   $libprojz = new AMProjectLibraryEntry($_REQUEST["frm_codeProjeto"]);
   $libprojz->libraryExist();
   $proj = new AMProjeto;
   $proj->codeProject = $_REQUEST["frm_codeProjeto"];
   $proj->load();
   $titulo = "Projeto $proj->title";
   $imagem = $proj->image;
   //pega o id da lib do proj
   $lib = $proj->getLibrary();
   break;

 default:  //se o frm_type vier vaziu ele considera como biblioteca de usuario
   
   $a = new AMUserLibraryEntry($_SESSION["user"]->codeUser);
   $a->libraryExist(); //testa se existe a biblioteca desse usuario e se nao, ele cria ela
   $user = $_SESSION["user"]->codeUser;
   $titulo = $_SESSION["user"]->name;
   $imagem = $_SESSION["user"]->foto;
   //pega o id da bib do user
   $lib = $a->getLibrary("$user");
   break;
}

$library = new AMLibrary;
$library->setLibrary($lib);

switch( $_REQUEST["opcao"] ){

 case "save":
   $ret =  $library->saveEntry();   
   if($ret) {
     $page->addMessage($_language["file_successful_sent"]);
   }
   else {
     $page->addError($_language["error_send_file"]);     
   }
   break;   

 case "delete":   
   $library->deleta($_REQUEST["id"]);
   $page->addMessage($_language["file_successful_delete"]);
   break;
 case "download":
   $file = new AMArquivo;
   $file->codeArquivo = $_REQUEST["codeArquivo"];
   $file->load();
   $file->nome = addslashes($file->nome);
   header("Content-Type: application/octet-stream");
   header("Content-Disposition:attachment; filename=$file->nome");
   header("Content-Length: ".$file->tamanho);
   header("Content-Transfer-Encoding: binary");
   echo $file->dados;
   break;
 case "share":
   break;
}

/**** CONTA ARQUIVOS DO PROJETO ****/
$count_img = $library->countBooks("image");
$count_text = $library->countBooks("text");
$count_audio = $library->countBooks("audio");
$count_video = $library->countBooks("video");
$count_others = $library->countBooks("other");
$count_all = $count_img + $count_text + $count_audio + $count_video + $count_others ;   //total de arquivos
/***********************************/


// viewer starts at this point

$ver = $_REQUEST["ver"];
$flag_on = $_REQUEST["flag_on"];
$conteudo .= "<table cellpadding='0' cellspacing='0' border='0'>";
$conteudo .= "<tr><td background='$_CMAPP[media_url]/images/top_bg_traco.gif'><img src='$_CMAPP[media_url]/images/dot.gif' width='1' height='4' border='0'></td></tr>";
$conteudo .= "<tr><td><img src='$_CMAPP[media_url]/images/dot.gif' width='20' height='20'></td></tr>";
$conteudo .= "<tr><td valign='top'>";
$conteudo .= "<table cellpadding='0' cellspacing='0' bgcolor='#F4F2F8' border='0' width='500'>";
$conteudo .= "<tr><td width='10'><img src='$_CMAPP[media_url]/images/box_biblio_01.gif' width='10' height='10' border='0'></td>";
$conteudo .= "<td><img src='$_CMAPP[media_url]/images/dot.gif' width='10' height='10' border='0'></td>";
$conteudo .= "<td width='10'><img src='$_CMAPP[media_url]/images/box_biblio_02.gif' width='10' height='10' border='0'></td>";
$conteudo .= "</tr><tr><td><img src='$_CMAPP[media_url]/images/dot.gif' width='10' height='10' border='0'></td>";
$conteudo .= "<td valign='top'><table cellpadding='0' cellspacing='0' border='0' width='100%'>";
$conteudo .= "<tr><td valign='top'><table cellpadding='0' cellspacing='0' border='0' width='280'><tr>";
$conteudo .="<td class='titbiblioteca' background='$_CMAPP[media_url]/images/img_tit_biblioteca.gif'><img src='$_CMAPP[media_url]/images/dot.gif' width='42' height='37' border='0' align='absmiddle'>$titulo</td>";
$conteudo .= "</tr></table></td><td align='right'><img src='$_CMAPP[media_url]/imagewrapper.php?method=db&frm_codeArquivo=$imagem' width=80% height=80% '></td>";
$conteudo .= "</tr></table></td><td><img src='$_CMAPP[media_url]/images/dot.gif' width='10' height='10' border='0'></td>";
$conteudo .= "</tr>";
//monta a interface da biblioteca, listando os arquivos, SE TIVER
if($count_all != 0 ){
  for($i=0;$i<5;$i++){//sao 5 categorias de arquivos
    if($i == 0){
      $lista = 0;//nao lista thumbs
      $title_icon = "textos";
      $acao = "docs";
      $conta_arquivo = $count_text;
    }
    elseif($i == 1){
      $lista = 1; //lista thumbs
      $title_icon = "imagens";
      $acao = "img";
      $conta_arquivo = $count_img;
      }
    elseif($i == 2){
      $lista = 0;
      $title_icon = "audio";
      $acao = "audio";
      $conta_arquivo = $count_audio;
    }
    elseif($i == 3){
      $lista = 0;
      $title_icon = "video";
      $acao = "video";
      $conta_arquivo = $count_video;
    }
    elseif($i == 4){
      $lista = 0;
      $title_icon = "outros";
      $acao = "outros";
      $conta_arquivo = $count_others;
    }
    if($conta_arquivo != 0){ //se nao tiver arquivo nem mostra!
      $conteudo .= "<tr><td><img src='$_CMAPP[media_url]/images/dot.gif' width='10' height='10' border='0'></td>";
      $conteudo .= "<td valign='top'><table cellpadding='0' cellspacing='0' border='0' width='100%'>";
      $conteudo .= "<tr bgcolor='#F9F9FA'><td colspan='5' align='left' valign='top'><table cellpadding='0' cellspacing='0' border='0'>";
      $conteudo .= "<tr><td>";
      $conteudo .= "<img src='$_CMAPP[media_url]/images/img_blt_$title_icon.gif'border='0'></td>";
      $conteudo .= "<td valign='top'><font class='blt_subtitulo'><img src='$_CMAPP[media_url]/images/dot.gif' width='12' height='13'><br>&nbsp;&nbsp;- $conta_arquivo  $_language[files]</font></td>";
      if($lista == 1){//caso seja a vez de listarmos a categoria de imagem temos a opcao de preview por thumbs
	if($flag_on == 1){
	  $flag_on = 0;
	  $imgthumb = "$_CMAPP[media_url]/images/icon_thumb_on.gif";
	  $imglink  = "$_SERVER[PHP_SELF]?frm_type=$_REQUEST[frm_type]&frm_codeProjeto=$_REQUEST[frm_codeProjeto]&flag_on=$flag_on"; 
	}
	else{
	  $flag_on = 1;
	  $imgthumb = "$_CMAPP[media_url]/images/icon_thumb_off.gif";
	  $imglink  = "$_SERVER[PHP_SELF]?frm_type=$_REQUEST[frm_type]&frm_codeProjeto=$_REQUEST[frm_codeProjeto]&ver=thumbs&flag_on=$flag_on";
	}
	  $conteudo .= "<td valign='middle' align='right' width='320'><a href='$imglink'><img src='$imgthumb'></a></td>";
      }
      $conteudo .= "</tr></table></td></tr>";				
      $conteudo .= "<tr><td colspan='5' background='$_CMAPP[media_url]/images/img_blt_pontilhado.gif'><img src='$_CMAPP[media_url]/images/dot.gif' width='1' height='1'></td></tr>";
      
      if($ver == ""  || $lista == 0){	
	$ret = $library->busca($acao);
	if($ret != ""){
	  $conteudo .= "<tr bgcolor='#C6BFD8'>";
	  $conteudo .= "<td class='blt_linha_tipo' width='14'><img src='$_CMAPP[media_url]/images/dot.gif' width='1' height='1'></td>";
	  $conteudo .= "<td class='blt_linha_tipo' width='200'>$_language[name]</td>";
	  $conteudo .= "<td class='blt_linha_tipo' width='70'>$_language[date]</td>";
	  $conteudo .= "<td class='blt_linha_tipo' width='50'>$_language[size]</td>";
	  $conteudo .= "<td class='blt_linha_tipo' width='70'>$_language[action]</td>";
	  $conteudo .= "</tr>";
	  foreach($ret as $item){
	    //arruma os icones..
	    $mimeType = explode("/",$item->tipoMime);
	    switch($mimeType[1]){
	    case "pdf":
	      $icon = "$_CMAPP[media_url]/images/icon_pdf.gif";
	      break;
	    case "msword":
	      $icon = "$_CMAPP[media_url]/images/icon_img01.gif";
	      break;
	    case "jpeg":
	      $icon = "$_CMAPP[media_url]/images/icon_img03.gif";
	      break;
	    case "png":
	      $icon = "$_CMAPP[media_url]/images/icon_img02.gif";
	      break;
	    case "gif":
	      $icon = "$_CMAPP[media_url]/images/icon_img02.gif";
	      break;
	    case "x-shockwave-flash":
	      $icon = "$_CMAPP[media_url]/images/icon_swf.gif";
	      break;
	    case "html":
	      $icon = "$_CMAPP[media_url]/images/icon_html.gif";
	      break;
	    case "zip":
	      $icon = "$_CMAPP[media_url]/images/icon_zip.gif";
	      break;
	    case "vnd.sun.xml.impress":
	      $icon = "$_CMAPP[media_url]/images/icon_apresenta.gif";
	      break;
	    case "vnd.sun.xml.writer":
	      $icon = "$_CMAPP[media_url]/images/icon_img01.gif";
	      break;    
	    case "vnd.ms-powerpoint":
	      $icon = "$_CMAPP[media_url]/images/icon_apresenta.gif";
	      break;
	    case "plain":
	      $icon = "$_CMAPP[media_url]/images/icon_apresenta.gif";
	      break;
	    default:
	      if($mimeType[0] == "audio"){
		$icon = "$_CMAPP[media_url]/images/icon_som.gif";
	      }
	      elseif($mimeType[0] == "video"){
		$icon = "$_CMAPP[media_url]/images/icon_video.gif";
	      }
	      else{
		$icon = "$_CMAPP[media_url]/images/icon_outro.gif";
	      }	      
	      break;
	    }
	    //------------------------------
	    if($cor == 1){
	      $cor_css = "a";
	      $cor = 0;
	    }
	    elseif($cor == 0){
	      $cor_css = "b";
	      $cor = 1;
	    }
	    //arruma o tamanho do arquivo para mostra em kb, nao em bytes.
	    $size = $item->tamanho / 1024;	    
	    $size = explode(".",$size); 
	    if($size[0] == 0){
	      $resto = str_split($size[1],1);
	      if($resto[0] == 0){
		$size[0] = "> 0.1";
	      }
	      else{
		$size[0] = "0.".$resto[0];
	      }
	    }
	    //------
	    $conteudo .= "<tr id='library_item_$item->codeArquivo' >";
	    $js = "Library_toogleHighlightLine('$item->codeArquivo','$cor_css')";
	    $js = "onMouseover=\"$js\" onMouseout=\"$js\"";
	    $conteudo .= "<td class='blt_col_$cor_css' $js width='13'><img src='$icon'></td>";
	    $conteudo .= "<td class='blt_col_$cor_css' $js id='first_column'>&raquo; $item->nome</td>";
	    $conteudo .= "<td class='blt_col_$cor_css' $js> ".date("d/m/Y",$item->tempo)."</td>";
	    $conteudo .= "<td class='blt_col_$cor_css' $js> $size[0]  Kb</td>";
	    $link = "$_SERVER[PHP_SELF]?frm_type=$_REQUEST[frm_type]&frm_codeProjeto=$_REQUEST[frm_codeProjeto]&opcao=delete&id=$item->codeArquivo";
	    $conteudo .= "<td class='blt_col_$cor_css' $js><a href='$_SERVER[PHP_SELF]?frm_type=$_REQUEST[frm_type]&frm_codeProjeto=$_REQUEST[frm_codeProjeto]&opcao=download&codeArquivo=$item->codeArquivo'><img src='$_CMAPP[media_url]/images/img_blt_ico_baixar.gif' width='17' height='14' alt='DOWNLOAD' border='0' hspace='2'></a><img onclick='Library_delFile($item->codeArquivo,\"$link\");' src='$_CMAPP[media_url]/images/img_blt_ico_excluir.gif' width='17' height='14' alt='EXCLUIR' border='0' class='cursor'>";

	    //AQUI PRECISA VIR O TESTE PRA VER SE O ARQUIVO  EH COMPARTILHADO OU NAO...E FAZER A MANUTENCAO DOS OLHOS
	    $share = $library->isShared($item->codeArquivo);

	    if($share == "true"){	      
	      $eyeicon = "img_blt_ico_eye_on.gif";
	      $id_E = "shared_$item->codeArquivo";
	    }
	    else{
	      $eyeicon = "img_blt_ico_eye_off.gif";
	      $id_E = "unshared_$item->codeArquivo";
	    }
	    $conteudo .= "<img src=$_CMAPP[media_url]/images/$eyeicon id='$id_E' onClick = \"AMShare.share(this.id)\" alt='COMPARTILHAR' border='0' class='cursor'>";
    	    $conteudo .= "</td></tr>";	  
	  }
	}	
      }
      elseif($ver == "thumbs" && $lista == 1){//exibe as img como thumbnails	
	$ret = $library->buscaThumbs();
	if($ret != ""){
	  foreach($ret as $item){	      
	    if($cor == 1){
	      $cor_css = "a";
	      $cor = 0;
	    }
	    elseif($cor == 0){
	      $cor_css = "b";
	      $cor = 1;
	    }
	    
	    if($broke_line == 0){
	      $conteudo .=  "<tr bgcolor='#f4f2f8'>";
	    }
	    $broke_line++;	   
	    $thumb = new AMLibraryThumb;
	    $thumb->codeArquivo = $item->codeArquivo;
	    $thumb->load();	  	    
	    
	    $conteudo .=  "<td class='blt_col_$cor_css' width='120' align='center'><br>";
	    $page->add($conteudo);
	    $page->add($thumb->getView());
	    $conteudo = "<br>$item->nome<br> <a href='$_SERVER[PHP_SELF]?frm_type=$_REQUEST[frm_type]&frm_codeProjeto=$_REQUEST[frm_codeProjeto]&opcao=download&codeArquivo=$item->codeArquivo'><img src='$_CMAPP[media_url]/images/img_blt_ico_baixar.gif'></a><img onclick='Library_delFile($item->codeArquivo,\"$link\");' src='$_CMAPP[media_url]/images/img_blt_ico_excluir.gif' class='cursor'>";
	    
	    $share = $library->isShared($item->codeArquivo);
	    
	    if($share == "true"){	      
	      $eyeicon = "img_blt_ico_eye_on.gif";
	      $id_E = "shared_$item->codeArquivo";
	    }
	    else{
	      $eyeicon = "img_blt_ico_eye_off.gif";
	      $id_E = "unshared_$item->codeArquivo";
	    }
	    $conteudo .= "<img src=$_CMAPP[media_url]/images/$eyeicon id='$id_E' onClick = \"AMShare.share(this.id)\" alt='COMPARTILHAR' border='0' class='cursor'>";
	    
	    $conteudo.= "</td>";
	    
	    if($broke_line == 3){ // 3 thumbs por coluna :) para 2 / col, bote 2.. e assim por diante.
	      $conteudo .= "</tr>";
	      $broke_line = 0;
	    }
	  }
	}
      }
      $conteudo .= "<tr><td colspan='3'><img src='$_CMAPP[media_url]/images/dot.gif' width='10' height='20' border='0'></td></tr>";
      $conteudo .= "</table></td>"; 
      }
   }
 }
else{
  $page->addError($_language["no_all"]);// nao tem nada para ser mostrado...bib vazia
}
$conteudo .= "<td><img src='$_CMAPP[media_url]/images/dot.gif' width='10' height='10' border='0'></td></tr>";
$conteudo .= "<tr><td><img src='$_CMAPP[media_url]/images/box_biblio_03.gif' width='10' height='10' border='0'></td>";
$conteudo .= "<td><img src='$_CMAPP[media_url]/images/dot.gif' width='10' height='10' border='0'></td>";
$conteudo .= "<td><img src='$_CMAPP[media_url]/images/box_biblio_04.gif' width='10' height='10' border='0'></td></tr>";
$conteudo .= "<tr><td colspan='3' bgcolor='#C3B6E4'><img src='$_CMAPP[media_url]/images/dot.gif' width='10' height='4' border='0'></td></tr>";
$conteudo .= "<tr><td colspan='3' bgcolor='#E9E5F5' valign='top'><img src='$_CMAPP[media_url]/images/dot.gif' width='10' height='30' border='0'><br>";
$conteudo .= "<table cellpadding='5' cellspacing='0' border='0' align='center'>";
$conteudo .= "<tr><td><img src='$_CMAPP[media_url]/images/img_blt_enviarbiblio.gif'></td>";
$conteudo .= "<td><form enctype='multipart/form-data' action='$_SERVER[PHP_SELF]' method='post' name='upload' onSubmit='Library_checkForm(this)'>";
$conteudo .= "<input name='upload' type='file'><input type='hidden' name='nomeCampo' value='upload'><input type='hidden' name='MAX_FILE_SIZE' value='2048'><input type='hidden' name='frm_type' value='$_REQUEST[frm_type]'><input type='hidden' name='frm_codeProjeto' value='$_REQUEST[frm_codeProjeto]'><input type='hidden' name ='opcao' value ='save'></td>";
$conteudo .= "<td><input type='submit' value='$_language[send]'></form></td></tr>";
$conteudo .= "</table><br></td></tr><tr>";
$conteudo .= "<td bgcolor='#FFFFFF'><img src='$_CMAPP[media_url]/images/box_biblio_up01.gif' width='10' height='10' border='0'></td>";
$conteudo .= "<td bgcolor='#E9E5F5'><img src='$_CMAPP[media_url]/images/dot.gif' width='1' height='1' border='0'></td>";
$conteudo .= "<td bgcolor='#FFFFFF'><img src='$_CMAPP[media_url]/images/box_biblio_up02.gif' width='10' height='10' border='0'></td></tr>";
$conteudo .= "</table><br><br><!-- fim corpo do diario --></td></tr></table>";
$page->add($conteudo);

echo $page;
?>