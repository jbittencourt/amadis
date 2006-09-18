<?

interface AMThumbinaiableImage {
  
  /**
   * Returns a thumbnail of the image.
   * 
   * The getThumb method should returns a CMHTMLObj with
   * the thumnail of the current image to be printed in an page. 
   **/
  static public function getThumb($obj,$smallthumb=false);

}

?>