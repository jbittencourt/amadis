<?

include_once("../../config.inc.php");

function notfound() {
    header("HTTP/1.0 404 Not Found");
    exit;
}

if(!isset($_REQUEST[ni])) {
    notfound();
}

$imagem = $_SESSION[imagem_at][$_REQUEST[ni]];


// check if file exists
if (!file_exists("$pathtemp/$imagem[tmp_name]")) {
    notfound();
}

// retrieve file info
$imginfo = getimagesize("$pathtemp/$imagem[tmp_name]");
$maxX = 70;
$maxY = 70;

// load image

switch ($imginfo[2]) { 
    case 1:     // gif
        $img_in = imagecreatefromgif("$pathtemp/$imagem[tmp_name]") or notfound();
        if (!isset($tipoimg)) {
            $tipoimg = "gif";
        }
        break;
    case 2:     // jpg
        
        $img_in = imagecreatefromjpeg("$pathtemp/$imagem[tmp_name]") or notfound();
        if (!isset($tipoimg)) {
            $tipoimg = "jpg";
        }
        break;
    case 3:     // png
        $img_in = imagecreatefrompng("$pathtemp/$imagem[tmp_name]") or notfound();
        if (!isset($tipoimg)) {
            $tipoimg = "png";
        }
        break;
    default:
        notfound();
}



// check for maximum width and height
if (isset($maxX)) {
    if ($maxX < imagesx($img_in)) {
        $imagem[width] = $maxX;
    }
}
if (isset($maxY)) {
    if ($maxY < imagesy($img_in)) {
        $imagem[height] = $maxY;
    }
}

// check for need to resize
if (isset($imagem[height]) or isset($imagem[width])) {
    // convert relative to absolute
    if (isset($imagem[width])) {
        if (strstr($imagem[width], "%")) {
            $imagem[width] = (intval(substr($imagem[width], 0, -1)) / 100) *
                          $imginfo[0];
        }
    }
    if (isset($imagem[height])) {
        if (strstr($imagem[height], "%")) {
            $imagem[height] = (intval(substr($imagem[height], 0, -1)) / 100) *
                          $imginfo[1];
        }
    }

    // resize
    if (isset($imagem[width]) and isset($imagem[height])) {
        $out_w = $imagem[width];
        $out_h = $imagem[height];
    } elseif (isset($imagem[width]) and !isset($imagem[height])) {
        $out_w = $imagem[width];
        $out_h = $imginfo[1] * ($imagem[width] / $imginfo[0]);
    } elseif (!isset($imagem[width]) and isset($imagem[height])) {
        $out_w = $imginfo[1] * ($imagem[height] / $imginfo[0]);
        $out_h = $imagem[height];
    } else {
        $out_w = $imagem[width];
        $out_h = $imagem[height];
    }
    
    // new image in $img_out
    $img_out = imagecreate($out_w, $out_h);
    imagecopyresized($img_out, $img_in, 0, 0, 0, 0, imagesx($img_out),
               imagesy($img_out), imagesx($img_in), imagesy($img_in));
} else {
    // no resize needed
    $img_out = $img_in;
}

// check for a given jpeg-quality, otherwise set to default
if (!isset($imagem[qualidade])) {
    $imagem[qualidade] = 75;
}

// returning the image
switch ($tipoimg) {
    case "gif":
        header("Content-type: image/gif");
        imagegif($img_out);
        exit;
    case "jpg":
        header("Content-type: image/jpeg");
        imagejpeg($img_out, "", $imagem[qualidade]);
        exit;
    case "png":
        header("Content-type: image/png");
        imagepng($img_out);
        exit;
   default:
        notfound();
}

?>
