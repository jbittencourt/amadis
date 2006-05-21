<?

/**
 * This subPackage answer for the users albums.
 * 
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMAlbum
 * @version 1.0
 * @author Cristiano S Basso <csbasso@lec.ufrgs.br>
 */

class  AMAlbum extends CMObj {
  
  public function configure() {
    $this->setTable("Album");

    $this->addField("code",CMObj::TYPE_INTEGER,4,1,0,1);
    $this->addField("codeUser",CMObj::TYPE_INTEGER,4,1,0,0);
    $this->addField("codePhoto",CMObj::TYPE_INTEGER,4,1,0,0);
    $this->addField("comments",CMObj::TYPE_VARCHAR,100,1,0,0);    
    $this->addField("time", CMObj::TYPE_INTEGER, 4,1,0,0);
    
    $this->addPrimaryKey("code");
  }

  public function getMyPhotos(){
    try{
      $q = new CMQuery('AMAlbum');
      $q->setFilter("Album.codeUser = '$this->codeUser'");
      return $q->execute();
    }catch(CMDBNoRecord $w){}
  }
  
  public function saveEntry(){
    $formName = $_REQUEST['nomeCampo']; // recebe o nome do campo de tipo 'file'
    
    $file = new AMArquivo;
  
    //preenche os capos do arquivo

    $ext = AMImage::getValidImageExtensions();
    $achou=false;
    foreach($ext as $item){
      $g = explode(".",$_FILES[$formName]['name']);
      if($g[1] == $item){
	$achou = true;
      }
    }
    if(!$achou)
      return false;
    

    $file->nome = $_FILES[$formName]['name'];
    $file->nome = str_replace(" ", "_",$file->nome);
    $file->tipoMime = $_FILES[$formName]['type'];
    $file->tamanho = $_FILES[$formName]['size'];
    $file->tempo = time();   

    
    if($_FILES[$formName]['tmp_name'] == "")
      return false;
    
    
    $file->dados  = implode("",file($_FILES[$formName]['tmp_name']));
    
    $d = $file->dados;
    if(empty($d))      
      return false;
    
    try {
      $file->save();	//salva o arquivo
      $this->codePhoto = $file->codeArquivo;
      $this->codeUser = $_SESSION['user']->codeUser;
      $this->comments = $_REQUEST['comment'];
      $this->time = time();
      $this->save();
    }catch(CMException $e){
      die($e->getMessage());
    }  
  }
  

  public function editComment($codePhoto, $comment){
    $this->codePhoto = $codePhoto;
    try{
      $this->load();
    }catch(CMException $e){}

    $this->comments = $comment;
    
    try{
      $this->save();
    }catch(CMException $e){}
  }


  public function deleta($id){
    
    $this->codePhoto = $id;
    try{
      $this->load();
    }catch(CMException $e){}
    $file = new AMArquivo;    
    $file->codeArquivo = $id;    
    try{
      $file->load();  
      $this->delete();
      $file->delete();
    }catch(CMException $e){}      
  }  
  
  public function delete(){
    parent::delete();
  }
}
?> 