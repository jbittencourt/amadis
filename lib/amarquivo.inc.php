<?
/**
 * This class models a file that should be stored in the database.
 * 
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access  private
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 * @see AMImage
 **/
class AMArquivo extends CMObj {

   public function configure() {
     $this->setTable("Arquivo");

     $this->addField("codeArquivo",CMObj::TYPE_INTEGER,11,1,0,1);
     $this->addField("dados",CMObj::TYPE_BLOB,16777215,1,0,0);
     $this->addField("tipoMime",CMObj::TYPE_VARCHAR,100,1,0,0);
     $this->addField("tamanho",CMObj::TYPE_INTEGER,11,1,0,0);
     $this->addField("nome",CMObj::TYPE_VARCHAR,30,1,0,0);
     $this->addField("metaDados",CMObj::TYPE_VARCHAR, 255,1,0,0);
     $this->addField("tempo",CMObj::TYPE_INTEGER,11,1,0,0);

     $this->addPrimaryKey("codeArquivo");
  }

  /**
   * Load an image from the request to the object.
   *
   * PHP handles files uploads with a predefined bidimensional array $_FILES. This
   * array contains various informations about the file being uploaded and a pointer
   * to the temporary file in the servers filesystem. This information should be handled
   * by the user to store the file in it's persistent location. This method, handles this
   * process, using the information provided by PHP and Apache to fill the AMArquivo
   * properties.
   *
   * @param string $inputName The name of the <INPUT type=file> element in the form.
   **/
  public function loadFileFromRequest($formName) {
    $this->nome = $_FILES[$formName]['name'];
    $this->tipoMime = $_FILES[$formName]['type'];
    $this->tamanho = $_FILES[$formName]['size'];
    $this->tempo = time();

    $this->dados  = implode("",file($_FILES[$formName]['tmp_name']));

    $d = $this->dados;
    if(empty($d)) {
      Throw new AMException("Nao consegui ler o arquivo ".$_FILES[$formName]['tmp_name'].".");
    }

  }


}

?>
