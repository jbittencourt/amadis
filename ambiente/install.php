<?
include('install_suport.php');

function note($array) { echo "<pre>Men:\n"; print_r($array); echo "</pre>"; }
$pag = implode("\n", file('../templates/install_template.php'));

//output stream
$out = array();

//system diretories
$basedir = substr(getcwd(), 0, -9);
$config_xml = $basedir.'/etc/config.xml';
$config_php = $basedir.'/ambiente/config.inc.php';
$pages = $basedir.'/ambiente/paginas';
$users_pages = $paginas.'/users';
$project_pages = $paginas.'/projetos';

//Check requires

//Apache 2 or superior is required;
if(version_compare(substr(substr(apache_get_version(), 0, 13),-6), '2.0.1', '>=')) {
  $out[] = "<br><h4>APACHE_VERSION: ".apache_get_version()." [OK]</h4>";
  $ok = true;
} else {
  $out[] = "<br><b style='Color: red;'> You need Apache 2.0.1 or superior!</b>";
  $ok = false;
}

//PHP 5.0.1 or superior is required;
if(!version_compare(PHP_VERSION, '5.0.1', ">=")) {
  $out[] = "<br><b style='Color: red;'> You need PHP 5.0.1 or superior!<B>";
  $ok = false;
} else {
  $out[] = "<h4>PHP_VERSION: ".PHP_VERSION."</h4>";
  $ok = true;
}

//Check permisions in ambiente and media
$pBase = getPerms($basedir."/ambiente");
$pMedia = getPerms($basedir."/ambiente/media");

if($perm != "www" && $pMedia != "www") {
  $ok = false;
  $out[] = "<b style='color:red;'> You need set writable the following diretories:</b><br> ";
  $out[] = "<b>Execute the commands below in terminal:</b><br>";
  $out[] = "<h4><i>chmod 777 $basedir/ambiente<br>chmod 777 $basedir/ambiente/media</i></h4>";
}

if($ok) {
  $form = array();
  $form[] = "<form method=post action=#>";
  $form[] = "<fieldset><legend><b>Setup CM->DEVEL(Code Monkey Developer) and JPSpan-Ajax Framework</b></legend>";
  $form[] = "<table cellpadding='4'>";
  $form[] = "<tr><td>Put CM->DEVEL path:</td><td><input size='40' type='text' value='$_REQUEST[cmdevel]' name='cmdevel'></td></tr>";
  $form[] = "<tr><td>Put JPSpan path:</td><td><input size='40' type='text' value='$_REQUEST[jpspan]' name='jpspan'></td></tr>";
  $form[] = "</table><input type='hidden' name='action' value='setup'><input type='submit' value='Send'>";
  $form[] = "</fieldset></form>";
  
  if($_REQUEST[action]=='setup') {
    if(!empty($_REQUEST[cmdevel]) && !file_exists($_REQUEST[cmdevel].'/cmdevel')) {
      $ok = false;
      $form[3] = "<tr><td>Put CM->DEVEL path:</td><td bgcolor='#FF0000'><input size='40' value=$_REQUEST[cmdevel] type='text' name='cmdevel'></td></tr>";
    }
    
    if(!empty($_REQUEST[jpspan]) && !file_exists($_REQUEST[jpspan].'/JPSpan.php')) {
      $ok = false;
      $form[4] = "<tr><td>Put JPSpan path:</td><td bgcolor='#FF0000'><input size='40' value=$_REQUEST[jpspan] type='text' name='jpspan'></td></tr>";
      
    }
    $tmp = "<h4>CM->DEVEL_PATH: <i>$_REQUEST[cmdevel]</i><br>JPSpan_PATH: <i>$_REQUEST[jpspan]</i></h4>";
    $out[] = (!$ok ? "<b style='color: red;' >Check detached paths!</b>".implode("\n", $form): $tmp);
  } else {
    $out[] = implode("\n", $form);
    $ok = false;
  }
}

if($ok) {

  $out[] = "<h4>AMADIS_PATH: $basedir</h4>";

  $out[] = "<h4>AMADIS configuration</h4>";
  $out[] = "Create configurations files:";
  
  $out[] = "<b>config.xml file:</b> <i>$config_xml</i><br>";
  $out[] = "<b>config.inc.php file:</b> <i> $config_php</i><br>";
  $out[] = "Created config files: [OK]";
  //check homepages diretory
  if(file_exists($pages)) {
    $out[] = "<br><b style='color: red;'>You need create homepage's diretory.</b><br>";
    $out[] = "Execute as root, the commands below in terminal:<br><br>";
    $out[] = "<b><i>mkdir $pages<br>";
    $out[] = "chown www-data $pages<br>";
    $out[] = "chgrp www-data $pages</i></b><br><br>";
  } else {
//     if(@mkdir($users_pages, 0755)) $out[] = "";
//     mkdir($project_pages, 0755);
  }
  $out[] = "</fieldset><br>";  
    
}


echo str_replace("{CONTENT}", implode("\n", $out), $pag);


?>



