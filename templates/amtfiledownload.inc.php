<?php
/**
 * Provides a method to download a file from the database.
 *
 * When the developer uploads and store a file in the databese,
 * one of the most common tasks to be performed is to download
 * that file in some ocasion. This class provides a simple
 * method to make this download possible. It uses is very
 * simple, just passing an instance of the file to
 * be downloaded to the constructor, like de example above:
 *
 * <code>
 * <?php
 *     $file = new AMFile;
 *     $file->codeFile = $_REQUEST['code_image']; //an image code passed by the request;
 *     try {
 *        $file->load();
 *     } catch(CMDBNoRecord $e) {
 *        die("Image doesn't exists.");
 *     }
 *     $download = new AMTFileDownload($file);
 *     echo $download;
 *     die();
 * ?>
 * </code>
 * 
 * In this examplo, the file will be pushed to the users browser as an octet-stream, wich
 * will make it to be downloaded insted of showed.
 *
 **/
class AMTFileDownload {

    protected $file;


    public function __construct(AMFile $file) {
        $this->file = $file;
    }

    public function __toString() {
        global $_conf;
        
        $msie5 = 0;
    	if (eregi("IE 5",$_SERVER['HTTP_USER_AGENT'])) { $msie5 = 1; } 

        //$this->file->name = addslashes($this->file->name);
        header("Content-length: " . $this->file->size);
        header("Content-Transfer-Encoding: 8bit");
        
        if($msie5) {
            header("Content-type: application/force-download");
            header('Content-disposition: inline; filename="' . $this->file->name . '"');
        } else {
        	header("Content-Type: application/octet-stream");
        	header('Content-disposition: attachment; filename="' . $this->file->name . '"');
        }
        
        header("Content-Length: ".$this->size);
        

        $path = (string) $_conf->app->paths->files;
        $imagem = $path . DIRECTORY_SEPARATOR . $this->file->codeFile."_".$this->file->name;
        $handle = fopen ($imagem, "r");

        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                echo $buffer;
            }
            fclose($handle);
        }
        return '';
    }

}
