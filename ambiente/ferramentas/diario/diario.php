<?
$_CMAPP['notrestricted'] = True;
include("../../config.inc.php");

$_language = $_CMAPP['i18n']->getTranslationArray("diary");
$_CMAPP['smartform'] = array();
$_CMAPP['smartform']['language'] = $_language;



$pag = new AMTDiario();


if(!empty($_REQUEST['frm_action'])) {
  switch($_REQUEST['frm_action']) {
  case "A_comentario":
    $comentario = new AMDiarioComentario;
    $comentario->loadDataFromRequest(); // pega os dados do request e manda p/ o banco
    $comentario->time = time();
    $comentario->codeUser = $_SESSION['user']->codeUser;

    try {
      $comentario->save();
      $pag->addMessage($_language['msg_comments_saved']); //aviso em java script
    }
    catch(CMDBQueryError $erro) {
      $_REQUEST['frm_amerror'] = "comment_not_saved";
    }
    
    break;

    // ------------  teste para deletar posts
  case "A_delete":
    $deletar = new AMDiarioPost;
    $deletar->codePost = $_REQUEST['frm_codePost'];
    try {
      $deletar->load();
      if($deletar->countComments()>0){
	$pag->addError($_language['error_post_cannot_be_deleted']);
      }
      else{
	try{
	  $deletar->delete();
	}catch(CMObjException $exception) {
	  $pag->addError($_language['error_post_not_delete']);
	}
      }
    }
 
    catch(CMObjException $exception) {
      $pag->addError($_language['post_not_delete']);

    }
    break;
    // ------------  teste para deletar posts

  }
}


$default = true;

//tests if an user code was submited
//in this case show the diary of this
//user
if(!empty($_REQUEST['frm_codeUser'])) {
  $userDiario = new AMUser;
  $userDiario->codeUser = $_REQUEST['frm_codeUser'];
  try {
    $userDiario->load();
    $default = false;
  } Catch(CMDBNoRecord $e) {
    $pag->addError($_language['error_cannot_find_user']);
    echo $pag; 
    die();
  }
}
else {
  //if no user code was submited, test for
  //a message code, so we can load the
  //data for the query
  if(!(empty($_REQUEST['frm_codePost']))) {
    $post = new AMDiarioPost;
    $post->codePost = $_REQUEST['frm_codePost'];
    try {
      $post->load();
      $userDiario = new AMUser;
      $userDiario->codeUser = $post->codeUser;
      $userDiario->load();
      $default = false;
    } Catch(CMDBNoRecord $e) {
      $pag->addError($_language['error_post_does_not_exist']);
      echo $pag;
      die();
    }
    
    $_REQUEST['frm_calMonth'] = date("m",$post->tempo);
    $_REQUEST['frm_calYear'] = date("Y",$post->tempo);  
  }

}

//If there is codePost or codeUser submited, or there
//was some error in the load, the defautl behavior is
//to load the diary of the user.
if($default) {
  $userDiario = $_SESSION['user'];
}   

if(!empty($userDiario)) {
  $profile= new AMDiarioProfile;
  $profile->codeUser = $userDiario->codeUser;

  try {
    $profile->load();
    $title = $profile->tituloDiario;
    $text = $profile->textoProfile;
    $linkEditar = "editar.php?frm_action=editar";
  } 
  catch(CMDBNoRecord $exception) {
    $title=$_language['titulo_padrao'].' '.$userDiario->name;
    $linkEditar = "editar.php";
  }

  if(empty($_REQUEST['frm_calYear']) || empty($_REQUEST['frm_calMonth'])) {
    $_REQUEST['frm_calMonth'] = date('m',time());
    $_REQUEST['frm_calYear'] = date('Y',time());
  }

  $posts = $userDiario->listDiaryPosts($_REQUEST['frm_calMonth'],$_REQUEST['frm_calYear']);


  if($profile->image==0) {
    $image = new AMTFotoDiario($userDiario->foto); 
  }
  else {
    $image = new AMTFotoDiario($profile->image);
  }

     
  $caixa = new AMBoxDiario($posts,$userDiario->codeUser,$title,$image,$text);
  $caixa->setDate($_REQUEST['frm_calMonth'],$_REQUEST['frm_calYear']);
  if(!empty($_SESSION['user'])) {
    if($userDiario->codeUser==$_SESSION['user']->codeUser) {
      $caixa->addCabecalho("<br> <a class=\"diary_header\" href=\"postar.php\" > &raquo; $_language[escrever_nota] </a>");
      $caixa->addCabecalho("<br> <a class=\"diary_header\" href=\"$linkEditar\" > &raquo; $_language[editar_diario] </a>");
    }
  }

  $pag->add($caixa);
} else {
  $pag->addError($_language['error_user_not_logged']);
}

echo $pag; 

?>