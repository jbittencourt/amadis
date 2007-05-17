<?php

include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("rte");

if(!isset($_REQUEST['frm_codeForum'])){
	$_REQUEST['frm_codeForum'] = "";
}

$userBib = AMForum::loadImageLibrary($_REQUEST['frm_codeForum']);
$uOut = array();

if($userBib->__hasItems()) {
	foreach($userBib as $item) {
		$image = new AMLibraryThumb;
		$image->codeFile = $item->codeFile;
		try {
			$image->load();

			$meta = explode("|", $item->metadata);
			$url = $image->thumb->getThumbUrl();

			$click = "AddImage('../../media/thumb.php?frm_image=$item->codeFile&action=library', 'legend_$item->codeFile');";

			$uOut[] = "<div class='item' style=\"background-image: url('$url');\">";
			$uOut[] = "<div>";
			$uOut[] = "$item->name<br>$meta[0]x$meta[1] px / {$meta[2]}KB";
			$uOut[] = "<p><a onClick=\"toggle('legenda_$item->codeFile'); legend='legend_$item->codeFile';\">&raquo;Legenda</a></p>";
			$uOut[] = "</div>";
			$uOut[] = "<div class='buttonOK'>";
			$uOut[] = "<button onClick=\"$click\"><img src='$_CMAPP[images_url]/buttonOK.gif'></button></div>";
			$uOut[] = "<div id='legenda_$item->codeFile' style='display:none;'>";
			$uOut[] = "<input type='text' size='35' name='legend_$item->codeFile' id='legend_$item->codeFile'>";
			$uOut[] = "</div>";
			$uOut[] = "</div>";

		}catch (CMException $e) {

		}

	}
} else {
	//$pag->add("<p>Nao ha imagens na sua biblioteca</p>");
}

$pBib = AMForum::loadProjectImageLibrary();
if($pBib->__hasItems()) {
	foreach($pBib as $item) {
		$image = new AMLibraryThumb;
		$image->codeFile = $item->codeFile;

		try {
			$image->load();

			//$dimensions = $image->getSize();
			//$size = round($image->tamanho/1024);
			$url = $image->thumb->getThumbUrl();

			$pj = $item->proj[0]->codeProject;
			$pjTitle = $item->proj[0]->title;

			if(!isset($pOut[$pj])) $pOut[$pj] = array();

			$click = "AddImage('../../media/thumb.php?frm_image=$image->codeFile&action=library', 'legend_$image->codeFile');";
			$meta = explode("|", $item->files[0]->metadata);
			$pOut[$pj]['title'] = $pjTitle;
			$pOut[$pj][] = "<div class='item' style=\"background-image: url('$url');\">";
			$pOut[$pj][] = "<div>";
			$pOut[$pj][] = "{$item->files[0]->nome}<br>$meta[0]x$meta[1] px / {$meta[2]}KB";
			$pOut[$pj][] = "<p><a onClick=\"toggle('legenda_$image->codeFile'); legend='legend_$image->codeFile';\">&raquo;Legenda</a></p>";
			$pOut[$pj][] = "</div>";
			$pOut[$pj][] = "<div class='buttonOK'>";
			$pOut[$pj][] = "<button onClick=\"$click\"><img src='$_CMAPP[images_url]/buttonOK.gif'></button></div>";
			$pOut[$pj][] = "<div id='legenda_$image->codeFile' style='display:none;'>";
			$pOut[$pj][] = "<input type='text' size='35' name='legend_$image->codeFile' id='legend_$image->codeFile'>";
			$pOut[$pj][] = "</div>";
			$pOut[$pj][] = "</div>";

		}catch (CMEXception $e) {
			//
		}
	}
} else {
	//
}
if(isset($pOut)){
	foreach($pOut as $k=>$item) {
		$out[] = "<li class='Proj Normal' id='Project' onClick=\"toggle('Project|Proj$k');\">Imagens do Projeto $item[title]</li>";
		$out[] = "<script type='text/javascript'>idArray.push('Proj$k');</script>";
		$out[] = "<span id='Proj$k'>";
		unset($pOut[$k]['title']);
		$out[] = implode("\n", $pOut[$k]);
		$out[] = "</span>";
	}
}

?>

<html>
<head>
<style>@import url('<?=$_CMAPP[css_url]."/rte_ins_image.css"?>');</style>
<script type='text/javascript'>

var idArray = new Array();

function toggle(id) {
  if(id.indexOf("|") != -1) {
    var ids = id.split("|");
    hideAll(ids[1]);
    var span = document.getElementById(ids[1]);  
    var li = document.getElementById(ids[0]);
    changeStyle(li.id);
    if(span.style.display == 'block') 
      span.style.display = 'none';
    else span.style.display = 'block';
  } else {
   div = document.getElementById(id);
   if(div.style.display=='block')
     div.style.display = 'none';
   else div.style.display = 'block';
  }
}

function hideAll(id) {
  for(var i in idArray) {
    if(idArray[i] != id)
      document.getElementById(idArray[i]).style.display = 'none';
  }
}

function changeStyle(id, force) {
  var obj = document.getElementById(id);
  if(obj.className.indexOf("Normal") != -1) {
    var regEX = /Normal/gi;
    var tmp = obj.className.replace(regEX, "Selected");
  } else {
    var regEX = /Selected/gi;
    var tmp = obj.className.replace(regEX, "Normal");
    
  }
  obj.className = tmp;
}

function AddImage(imageURL, legenda) {
  var html = "<img style='border: solid #CCCCCC 2px; padding: 5px; margin: 5px; max-width: 450px;'";
  if (legenda != '') {
    var leg = document.getElementById(legenda);
    if(typeof(leg) != 'object') {
      html += " src='"+imageURL+"' title='"+legenda+"'>";
    }else {
      if(leg.value != '')
	html += " src='"+imageURL+"' title='"+leg.value+"'>";
      else html += " src='"+imageURL+"'>";
    }
  }else html += " src='"+imageURL+"'>";

  window.opener.insertHTML(html);
  window.close();
  return true;
}

</script>
</head>
<body>

<div id='mainbox'><img src='<?=$_CMAPP['imlang_url']."/boxTitulo.gif"?>'><br>
<div id='listBox'>
<ul>
	<li class='MyBib Selected' id='MyBib'
		onClick="toggle('MyBib|MyBibList');"><? echo $_language['image_from_my_AMADIS_lib']; ?>
	</li>
	<script type='text/javascript'>idArray.push('MyBibList');</script>
	<span id='MyBibList' style='display: block;'> <? echo implode("\n", $uOut); ?>
	</span>
	<?
	if(isset($out)){
		echo implode("\n", $out);
	}
?>
</ul>
</div>
<div id='listBox' style='height: 225px;'>
<ul>
	<li class='Upload Selected' id='UpFile'><? echo $_language['image_from_my_pc']; ?></li>
	<span id='File' style='display: block;'> <iframe src="sendImage.php"></iframe>
	</span>

	<li class='Internet Normal' id='Net'
		onClick="changeStyle(this.id); toggle('netBox');"><? echo $_language['image_from_internet']; ?></li>
	<span id='netBox'> <input title='Digite um endere&ccedil;o de imagem'
		type='text' size='38' id='imageURL' value='http://'>
	<button
		onClick="AddImage(document.getElementById('imageURL').value, '');"><img
		src='<?=$_CMAPP['images_url']."/buttonOK.gif"?>'></button>
	</span>
</ul>
</div>
<img src='<?=$_CMAPP['images_url']."/boxFooter.gif"?>'></div>
</body>
</html>
