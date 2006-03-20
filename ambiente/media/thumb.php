<?

include_once("../config.inc.php");

function notfound() {
  header("HTTP/1.0 404 Not Found");
  exit;
}

if(!isset($_REQUEST['frm_image'])) {
  notfound();
}

switch($_REQUEST['action']) {
 case "library":
   if(isset($_REQUEST['thumb'])) $image = new AMLibraryThumb;
   else $image = new AMImage;

   $image->codeArquivo = $_REQUEST['frm_image'];
   try {
     $image->load();
     if($image instanceof AMThumb) {
       $dados = $image->getView();
     }
     else $dados = $image->dados;
     header("Content-type: $image->tipoMime");
     echo $dados;
   }catch (CMException $e) {

     notfound();
   }
   break;

 default:
   $imagePath = $_CMAPP['path']."/ambiente/paginas/$_REQUEST[frm_image]";

   // check if file exists
   if (!file_exists("$imagePath")) {
     notfound();
   }
   
   $validTypes = AMImage::getValidImageTypes();

   // retrieve file info
   $imginfo = getimagesize("$imagePath");
   $maxX = 70;
   $maxY = 70;
   
   // load image
   
   switch ($imginfo[2]) { 
   case 1:     // gif
     $img_in = imagecreatefromgif("$imagePath") or notfound();
     if (!isset($tipoimg)) {
       $tipoimg = "gif";
     }
     break;
   case 2:     // jpg
     
     $img_in = imagecreatefromjpeg("$imagePath") or notfound();
     if (!isset($tipoimg)) {
       $tipoimg = "jpg";
     }
     break;
   case 3:     // png
     $img_in = imagecreatefrompng("$imagePath") or notfound();
     if (!isset($tipoimg)) {
       $tipoimg = "png";
     }
     break;
   default:
     notfound();
   }
   
   
   
   // check for maximum width and height
   if (isset($maxX)) {
     $imagesx = imagesx($img_in);
     if ($maxX < $imagesx) {
       $imagem['width'] = $maxX;
     }else $imagem['width'] = $imagesx;
   }
   if (isset($maxY)) {
     $imagesy = imagesy($img_in);
     if ($maxY < $imagesy) {
       $imagem['height'] = $maxY;
     }else $imagem['height'] = $imagesy;
   }
   
   $x0 = $imginfo[0];
   $y0 = $imginfo[1];
   
   $x1 = $imagem['width'];
   $y1 = $imagem['height'];
   
   //compute the new dimmensions of the image considering
   //the requested new dimensions and mantaining the
   //porportion betwen the sides;
   // x0,y0 = sizes of the original image
   // x1,y1 = requested new size of the image
   // xc,yc = computed new size of the image
   
   
   $dx = $x1/$x0;
   $dy = $y1/$y0;
   
   if($dx<$dy) {
     $xc = $x1;
     $yc = round($y0*$dx);
   }
   else {
     $xc = round($x0*$dy);
     $yc = $y1;
   }
   
   //resizing image
   //echo implode("",file($imagePath));die();
   $img_src = imagecreatefromstring(file_get_contents($imagePath));
   $img_dst = ImageCreateTrueColor($xc,$yc);
   
   $src_width = imagesx($img_src);
   $src_height = imagesy($img_src);
   
   imagecopyresampled($img_dst,$img_src,0,0,0,0,$xc,$yc,$src_width, $src_height);
   
   
   //captures the image
   ob_start();
   $dados = ob_get_contents();
   $tipoMime = "image/png";
   //clear the buffer
   ob_end_clean();
   
   //escreve a imagem
   header("Content-type: $tipoMime");
   imagepng($img_dst);
   break;
}
?>
