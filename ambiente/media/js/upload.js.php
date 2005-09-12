<?
include("../../config.inc.php");

$_language = $_CMAPP[i18n]->getTranslationArray("upload");


?>

function UploadDownload(form, dir, upload_type, codeProjeto, codCourse) {

  form.action += "?action=A_download&frm_upload_type="+upload_type+"&frm_dir="+dir;
  if(codeProjeto != "") form.action += "&frm_codeProjeto="+codeProjeto;
  else if(codCourse != "") form.action += "&frm_codCourse="+codCourse;
  form.submit();
  
}


function UploadCheckOverwrite(form, list){
  var msg = "<? echo $_language[overwrite_files]; ?>\n";
  var msgF = "";
  
  for(var i=0; i<5; i++) {
    var filePath = String(form.elements["frm_file_"+i].value);
    var fileName = filePath.substr(filePath.lastIndexOf("/")+1);
    for(var j in list.elements) {
      if(fileName == list.elements[j].value)
	msgF += " - " + fileName+ "\n";
    }
  }
  
  msg+=msgF;
  var len = parseInt(msgF.length);
  if(len != 0) {
    if(confirm(msg)) 
      return true;
    else return false;
  }else return true;
} 


//function UploadDelete(form, numItems, dir, upload_type, codeProjeto, codCourse) {
function UploadDelete(fileId) {
  
  var msg = "<? echo $_language[fields_to_delete]; ?>\n";

  form = AM_getElement("form_upload");

  if(numItems == '') {
    alert("<? echo $_language[not_delete_empty_diretory]; ?>");
    return false;
  } else numItems = parseInt(numItems);

  var cont = 0;
  
  if(fileId != null) {
    param = fileId.split(",");
    
    var obj = form.elements["frm_chk_row_"+param[1]];
    var name = new String(param[0]);
    msg = msg+" - "+name+"\n";
    cont++;

  }else {  

    for(var i = 1; i < numItems; i++) {
      
      var nameObj = "frm_chk_row_"+i;
      
      var obj = form.elements[nameObj];
      
      if(obj.checked == true) {
	var name = new String(obj.name);
	var realName = name.substr(9);
	msg = msg+" - "+realName+"\n";
	cont ++;
      }
    }
  }
  
  if(cont != 0) {
    if(confirm(msg)) {
      form.action = form.action+"?action=A_delete&frm_upload_type="+upload_type+"&frm_dir="+dir;
      if(codeProjeto != "") form.action += "&frm_codeProjeto="+codeProjeto;
      else if(codeCourse != "") form.action += "&frm_codCourse="+codeCourse;
      form.submit();
    }// else return false;
  }else alert("<? echo $_language[not_selected_files]; ?>");
}


function UploadNewFolder(urlBase, upload_type, dir, codeProjeto, codCourse) {
  var nomFolder, url;
  nomFolder = prompt("<? echo $_language[new_folder]; ?>");
  var checkNomFolder = new RegExp("[!@#$%^&*{}]");
  
  if (checkNomFolder.test(nomFolder) == false) {
    if(nomFolder == null) return false;
    else {
      url  = urlBase+"?action=A_create_dir&frm_upload_type="+upload_type;
      url += "&frm_dir="+dir+"&frm_dirName="+nomFolder;
      if(codeProjeto != "") url += "&frm_codeProjeto="+codeProjeto;
      else if(codCourse != "") url += "&frm_codCourse="+codCourse;
      redirectPage(url);
    }
  }else {
    alert("nome da pasta invalido");
  }
}

//name,id,type
function UploadOpenFile(file) {
  
  param = file.split(",");
  switch(param[2]) {
  case "img":
    handle = window.open(popUrlBase+"/"+param[0],"viewFile", "width=500, height=400, scrollbars=no, status=no");
    break;
   case "html":
     handle = window.open(popUrlBase+"/"+param[0],"viewFile", "width=700, height=600, status=no");
    break;
  }
}

function UploadUnzip(file) { 
  param = file.split(",");
  path = "action=A_unzip_file&frm_filename="+param[0];
  url  = baseUrl+"?frm_dir="+dir+"&frm_upload_type="+upload_type+"&frm_codeProjeto="+codeProjeto;
  url += "&frm_codeCourse="+codeCourse+"&"+path;
  location.href=url;
}

function UploadCheckValidName(name) {
  var checkNomFolder = new RegExp("[!@#$%^&*{}]");
  return checkNomFolder.test(name);
}

function UploadNewFile() {
  var nomFolder, url;
  
  nomFolder = prompt(new_file_name);
  
  if (!UploadCheckValidName(nomFolder)) {
    if(nomFolder == null) return false;
    else {
      url  = urlBase+"?action=A_create_file&frm_upload_type="+upload_type;
      url += "&frm_dir="+dir+"&frm_dirName="+nomFolder;
      if(codeProjeto != "") url += "&frm_codeProjeto="+codeProjeto;
      else if(codeCourse != "") url += "&frm_codCourse="+codeCourse;
      redirectPage(url);
    }
  }else {
    alert("nome da pasta invalido");
  }

  path = "action=A_create_file&frm_filename="+filename;
  
  url  = baseUrl+"?frm_dir="+dir+"&frm_upload_type="+upload_type+"&frm_codeProjeto="+codeProjeto;
  url += "&frm_codeCourse="+codeCourse+"&"+path;

}

function Upload_img(targetId) {
  //var menuContent = defaultContextMenuItems();
  
  var menuContent = new Array();
  menuContent.push(new Array("Deletar","UploadDelete(\""+targetId+"\");"));
  //menuContent.push(new Array("Renomear","UploadRename(\""+targetId+"\");"));
  menuContent.push(new Array("Abrir","UploadOpenFile(\""+targetId+"\");"));
  
  return menuContent;
}

function Upload_zip(targetId) {
  var menuContent = new Array();
  menuContent.push(new Array("Deletar","UploadDelete(\""+targetId+"\");"));
  //menuContent.push(new Array("Renomear","UploadRename(\""+targetId+"\");"));
  menuContent.push(new Array("Unzip","UploadUnzip(\""+targetId+"\");"));
  
  return menuContent;
  
}

function Upload_html(targetId) {
  var menuContent = new Array();
  menuContent.push(new Array("Deletar","UploadDelete(\""+targetId+"\");"));
  //menuContent.push(new Array("Renomear","UploadRename(\""+targetId+"\");"));
  menuContent.push(new Array("Editar","UploadEditFile(\""+targetId+"\");"));
  menuContent.push(new Array("Abrir","UploadOpenFile(\""+targetId+"\");"));

  return menuContent;
}

AM_registerHandlerId("Upload_img");
AM_registerHandlerId("Upload_zip");
AM_registerHandlerId("Upload_html");